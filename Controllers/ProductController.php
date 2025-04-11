<?php
require_once 'Models/ProductModel.php';
require_once 'Models/CategoryModel.php';
require_once 'Models/InventoryModel.php';

class ProductController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new ProductModel();
    }

// In ProductController.php
public function index()
{
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 4;
    
    $inventory = $this->model->getInventoryWithProductDetails($page, $perPage);
    $totalItems = $this->model->getInventoryCount();
    $totalPages = ceil($totalItems / $perPage);

    $this->views('products/list', [
        'inventory' => $inventory,
        'categories' => $this->model->getCategories(),
        'products' => $this->model->getProducts(),
        'currentPage' => $page,
        'totalPages' => $totalPages
    ]);
}

    public function updatePrice()
    {
        $this->handlePriceUpdate('updateProductPrice');
    }

    private function handlePriceUpdate($method)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Invalid request method. Only POST requests are allowed.']);
            exit;
        }

        $productId = $_POST['product_id'] ?? 0;
        $newPrice = $_POST['price'] ?? 0;

        if (!is_numeric($productId) || !is_numeric($newPrice) || $newPrice <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid product ID or price value.']);
            exit;
        }

        try {
            $updated = $this->model->$method($productId, $newPrice);
            echo json_encode(['success' => $updated, 'message' => $updated ? 'Price updated successfully.' : 'Failed to update the price.']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error occurred: ' . $e->getMessage()]);
        }
    }

    public function storeInventoryToProducts()
    {
        $inventoryItems = $this->model->getInventoryWithProductDetails();
        $categoryMap = array_column($this->model->getCategories(), 'id', 'name');

        $processedCount = $failedCount = 0;
        $errors = [];

        foreach ($inventoryItems as $item) {
            if (empty($item['inventory_product_name']) || empty($item['category_name']) || !isset($categoryMap[$item['category_name']])) {
                $errors[] = "Skipping item due to missing data: " . json_encode($item);
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
                        'image' => $item['image']
                    ]);
                } else {
                    $this->model->createProduct([
                        'category_id' => $categoryId,
                        'name' => $item['inventory_product_name'],
                        'barcode' => $item['barcode'] ?? null,
                        'price' => $item['amount'],
                        'quantity' => $item['quantity'],
                        'image' => $item['image']
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

    public function priceHistory($productId)
    {
        if (!is_numeric($productId)) {
            $_SESSION['error'] = "Invalid product ID.";
            header("Location: /products");
            exit;
        }
        $this->views('products/price_history', ['history' => $this->model->getPriceHistory($productId)]);
    }

    // In ProductController.php (unchanged)
    public function submitCart()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $cartItems = $data['cartItems'] ?? [];

        if (empty($cartItems)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Cart is empty']);
            exit;
        }

        try {
            $success = $this->model->processCartSubmissionWithSalesData($cartItems);
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Cart processed and sales data recorded successfully' : 'Failed to process cart'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Cart processing error: ' . $e->getMessage()]);
        }
    }

    public function syncQuantity()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $inventoryId = $data['inventoryId'] ?? null;
        $quantity = $data['quantity'] ?? 0;

        if (!is_numeric($inventoryId) || $quantity < 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid inventory ID or quantity']);
            exit;
        }

        try {
            $success = $this->model->syncProductQuantityFromInventory($inventoryId, $quantity);
            echo json_encode(['success' => $success, 'message' => $success ? 'Quantity synced successfully' : 'Failed to sync quantity']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}