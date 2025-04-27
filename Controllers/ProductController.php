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

    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 25;
        
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
            echo json_encode(['success' => false]);
            exit;
        }

        $productId = $_POST['product_id'] ?? 0;
        $newPrice = $_POST['price'] ?? 0;

        if (!is_numeric($productId) || !is_numeric($newPrice) || $newPrice <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false]);
            exit;
        }

        try {
            $updated = $this->model->$method($productId, $newPrice);
            echo json_encode([
                'success' => $updated,
                'type' => $updated ? 'success' : 'minor'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'type' => 'critical',
                'message' => 'Server error'
            ]);
        }
    }

    public function storeInventoryToProducts()
    {
        $inventoryItems = $this->model->getInventoryWithProductDetails();
        $categoryMap = array_column($this->model->getCategories(), 'id', 'name');

        $processedCount = $failedCount = 0;

        foreach ($inventoryItems as $item) {
            if (empty($item['inventory_product_name']) || empty($item['category_name']) || !isset($categoryMap[$item['category_name']])) {
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
                $failedCount++;
            }
        }

        $_SESSION['message'] = "Processed {$processedCount} products. Failed: {$failedCount}.";
        header("Location: /products");
        exit;
    }

    public function priceHistory($productId)
    {
        if (!is_numeric($productId)) {
            header("Location: /products");
            exit;
        }
        $this->views('products/price_history', ['history' => $this->model->getPriceHistory($productId)]);
    }

    public function submitCart()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $cartItems = $data['cartItems'] ?? [];

        if (empty($cartItems)) {
            http_response_code(400);
            echo json_encode(['success' => false]);
            exit;
        }

        try {
            $success = $this->model->processCartSubmissionWithSalesData($cartItems);
            echo json_encode([
                'success' => $success,
                'type' => $success ? 'success' : 'minor'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'type' => 'critical',
                'message' => 'Server error'
            ]);
        }
    }

    public function syncQuantity()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $inventoryId = $data['inventoryId'] ?? null;
        $quantity = $data['quantity'] ?? 0;

        if (!is_numeric($inventoryId) || $quantity < 0) {
            http_response_code(400);
            echo json_encode(['success' => false]);
            exit;
        }

        try {
            $success = $this->model->syncProductQuantityFromInventory($inventoryId, $quantity);
            echo json_encode([
                'success' => $success,
                'type' => $success ? 'success' : 'minor'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'type' => 'critical',
                'message' => 'Server error'
            ]);
        }
    }

    public function getProductByBarcode()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $barcode = $data['barcode'] ?? '';

        if (empty($barcode)) {
            http_response_code(400);
            echo json_encode(['success' => false]);
            exit;
        }

        try {
            $item = $this->model->getInventoryByBarcode($barcode);
            if ($item) {
                echo json_encode([
                    'success' => true,
                    'type' => 'success',
                    'item' => $item
                ]);
            } else {
                echo json_encode(['success' => false]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'type' => 'critical',
                'message' => 'Server error'
            ]);
        }
    }

    public function getProductPageByBarcode()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $barcode = $data['barcode'] ?? '';

        if (empty($barcode)) {
            http_response_code(400);
            echo json_encode(['success' => false]);
            exit;
        }

        try {
            $result = $this->model->getProductPageByBarcode($barcode);
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'type' => 'success',
                    'page' => $result['page'],
                    'item' => $result['item']
                ]);
            } else {
                echo json_encode(['success' => false]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'type' => 'critical',
                'message' => 'Server error'
            ]);
        }
    }

    public function deleteInventory()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $inventoryId = $data['inventoryId'] ?? null;

        if (!is_numeric($inventoryId)) {
            http_response_code(400);
            echo json_encode(['success' => false]);
            exit;
        }

        try {
            $success = $this->model->deleteInventory($inventoryId);
            echo json_encode([
                'success' => $success,
                'type' => $success ? 'success' : 'minor'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'type' => 'critical',
                'message' => 'Server error'
            ]);
        }
    }
}