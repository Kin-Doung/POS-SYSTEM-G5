<?php
require_once 'Models/ProductModel.php';
require_once './Models/CategoryModel.php';
require_once './Models/InventoryModel.php';

class ProductController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new ProductModel();
    }

    public function index()
    {
        $inventory = $this->model->getInventoryWithProductDetails();
        $categories = $this->model->getCategories();
        $products = $this->model->getProducts();

        $this->views('products/list', [
            'inventory' => $inventory,
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function updatePrice()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $newPrice = $_POST['price'];

            if (!is_numeric($newPrice) || $newPrice <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid price']);
                return;
            }

            $updated = $this->model->updatePrice($productId, $newPrice);

            echo json_encode([
                'success' => $updated,
                'message' => $updated ? 'Price updated successfully' : 'Failed to update price'
            ]);
        }
    }

    public function storeInventoryToProducts()
    {
        $inventoryItems = $this->model->getInventoryWithProductDetails();
        $categories = $this->model->getCategories();
        $categoryMap = [];

        foreach ($categories as $category) {
            $categoryMap[$category['name']] = $category['id'];
        }

        $processedCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($inventoryItems as $item) {
            if (empty($item['inventory_product_name']) || empty($item['category_name'])) {
                $errors[] = "Skipping item due to missing name or category: " . json_encode($item);
                $failedCount++;
                continue;
            }

            if (!isset($categoryMap[$item['category_name']])) {
                $errors[] = "Category '{$item['category_name']}' not found in database.";
                $failedCount++;
                continue;
            }

            $categoryId = $categoryMap[$item['category_name']];

            try {
                $existingProduct = $this->model->getProductByNameAndCategory($item['inventory_product_name'], $item['category_name']);
                if ($existingProduct) {
                    $this->model->updateProductFromInventory($existingProduct['id'], [
                        'category_id' => $categoryId,
                        'price' => $item['amount'],
                        'quantity' => $item['quantity'],
                        'image' => $item['image'],
                    ]);
                } else {
                    $this->model->createProduct([
                        'category_id' => $categoryId,
                        'name' => $item['inventory_product_name'],
                        'barcode' => $item['barcode'] ?? null,
                        'price' => $item['amount'],
                        'quantity' => $item['quantity'],
                        'image' => $item['image'],
                    ]);
                }
                $processedCount++;
            } catch (Exception $e) {
                $errors[] = "Error processing product '{$item['inventory_product_name']}': " . $e->getMessage();
                $failedCount++;
            }
        }

        $_SESSION['message'] = "Processed {$processedCount} products. Failed: {$failedCount}.";
        $_SESSION['errors'] = $errors;
        header("Location: /products");
        exit;
    }

    public function priceHistory($productId = null)
    {
        if (!$productId || !is_numeric($productId)) {
            $_SESSION['error'] = "Invalid product ID.";
            header("Location: /products");
            exit;
        }

        $history = $this->model->getPriceHistory($productId);
        $this->views('products/price_history', ['history' => $history]);
    }

    public function updateProductPrice()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Invalid request method. Only POST requests are allowed.']);
            exit;
        }

        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $newPrice = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;

        if ($productId <= 0 || $newPrice <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid product ID or price value.']);
            exit;
        }

        try {
            $updated = $this->model->updateProductPrice($productId, $newPrice);

            if ($updated) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Price updated successfully.',
                    'product_id' => $productId,
                    'new_price' => $newPrice
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update the price. Try again later.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error occurred while updating the price: ' . $e->getMessage()]);
        }
    }
}
