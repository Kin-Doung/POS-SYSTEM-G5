<?php
require_once 'Models/PurchaseModel.php';
require_once 'Models/InventoryModel.php';
require_once 'BaseController.php';
require_once './Models/CategoryModel.php';

class PurchaseController extends BaseController
{
    private $model;
    private $categoryModel;
    private $inventoryModel;

    public function __construct()
    {
        $this->model = new PurchaseModel();
        $this->categoryModel = new CategoryModel();
        $this->inventoryModel = new InventoryModel();
    }

    public function index()
    {
        $purchases = $this->model->getPurchases();
        $this->views('purchase/list', ['purchases' => $purchases]);
    }

    public function create()
    {
        $categories = $this->categoryModel->getCategory();
        $inventoryItems = $this->inventoryModel->getInventoryWithCategory();
        $this->views('purchase/create', [
            'categories' => $categories,
            'inventoryItems' => $inventoryItems
        ]);
    }

    public function store()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method.');
            }

            $products = $this->processProductData();
            if (empty($products)) {
                throw new Exception('No valid products to insert.');
            }

            // Start transaction
            $this->model->startTransaction();

            // Insert into purchase table
            if (!$this->model->insertProducts($products)) {
                throw new Exception('Failed to insert products into purchase table.');
            }
            error_log("Successfully inserted products into purchase table.");

            // Update inventory for each product
            foreach ($products as $index => $product) {
                error_log("Processing product {$index}: product_name = {$product['product_name']}, barcode = " . ($product['barcode'] ?: 'empty'));
                $this->updateInventory($product);
            }

            // Commit transaction
            $this->model->commitTransaction();
            error_log("Transaction committed successfully.");
            $this->redirect('/purchase', 'Products successfully added and inventory updated.');
        } catch (Exception $e) {
            // Attempt rollback safely
            try {
                $connection = $this->model->getConnection();
                if ($connection instanceof PDO && $connection->inTransaction()) {
                    $this->model->rollBackTransaction();
                    error_log("Transaction rolled back due to error.");
                }
            } catch (Exception $rollbackError) {
                error_log("Rollback error: " . $rollbackError->getMessage());
            }
            // Log the main error
            error_log("Store error: " . $e->getMessage());
            // Return user-friendly error
            echo json_encode(['success' => false, 'message' => 'Failed to add products: ' . $e->getMessage()]);
        }
    }

    private function updateInventory($product)
    {
        try {
            $existingItem = $this->inventoryModel->getInventoryItemByProduct($product['product_name'], $product['category_id']);
            error_log("Checked inventory for product_name = {$product['product_name']}, category_id = {$product['category_id']}, exists = " . ($existingItem ? 'yes' : 'no'));

            if ($existingItem) {
                $newQuantity = $existingItem['quantity'] + ($product['quantity'] ?? 0); // Increment quantity, default to 1 if not provided
                $barcode = $product['barcode'] !== '' ? $product['barcode'] : ($existingItem['barcode'] ?? null);
                $image = $product['image'] ?? $existingItem['image']; // Use new image if provided, else keep existing
                error_log("Updating inventory ID {$existingItem['id']}: quantity = {$newQuantity}, barcode = " . ($barcode ?: 'empty') . ", image = " . ($image ?: 'empty'));
                $result = $this->inventoryModel->updateInventory($existingItem['id'], [
                    'quantity' => $newQuantity,
                    'barcode' => $barcode,
                    'image' => $image
                ]);
                if (!$result) {
                    throw new Exception("Failed to update inventory for ID {$existingItem['id']}.");
                }
                error_log("Updated inventory ID {$existingItem['id']} successfully.");
            } else {
                $barcode = $product['barcode'] !== '' ? $product['barcode'] : null;
                error_log("Adding to inventory: product_name = {$product['product_name']}, barcode = " . ($barcode ?: 'empty') . ", image = " . ($product['image'] ?: 'empty'));
                $result = $this->inventoryModel->addToInventory([
                    'product_name' => $product['product_name'],
                    'category_id' => $product['category_id'],
                    'category_name' => $product['category_name'],
                    'quantity' => $product['quantity'] ??0, // Default to 1 if not provided
                    'image' => $product['image'] ?? null,
                    'barcode' => $barcode
                ]);
                if (!$result) {
                    throw new Exception("Failed to add product {$product['product_name']} to inventory.");
                }
                error_log("Added product {$product['product_name']} to inventory successfully.");
            }
        } catch (Exception $e) {
            error_log("Inventory update error for product {$product['product_name']}: " . $e->getMessage());
            throw new Exception("Inventory error for {$product['product_name']}: " . $e->getMessage());
        }
    }

    private function processProductData()
    {
        $products = [];
        if (!isset($_POST['product_name']) || !is_array($_POST['product_name'])) {
            error_log("No product data provided in POST.");
            echo json_encode(['success' => false, 'message' => 'No product data provided.']);
            return [];
        }

        foreach ($_POST['product_name'] as $key => $productName) {
            $categoryId = $_POST['category_id'][$key] ?? null;
            $categoryName = $this->getCategoryNameById($categoryId);

            if (!$categoryName) {
                error_log("Category with ID $categoryId not found.");
                echo json_encode(['success' => false, 'message' => "Category with ID $categoryId not found."]);
                return [];
            }

            $productName = htmlspecialchars(trim($productName));
            if (empty($productName)) {
                error_log("Product name is empty at index $key.");
                echo json_encode(['success' => false, 'message' => 'Product name is required.']);
                return [];
            }

            $barcode = isset($_POST['barcode'][$key]) ? htmlspecialchars(trim($_POST['barcode'][$key])) : '';
            error_log("Received barcode for product {$productName}: " . ($barcode ?: 'empty'));

            $image = $this->handleImageUpload($key);

            $products[] = [
                'product_name' => $productName,
                'category_id' => $categoryId,
                'category_name' => $categoryName,
                'image' => $image,
                'barcode' => $barcode,
                'quantity' => isset($_POST['quantity'][$key]) ? intval($_POST['quantity'][$key]) : 0 // Include quantity
            ];
        }
        error_log("Processed " . count($products) . " products successfully.");
        return $products;
    }

    private function getCategoryNameById($categoryId)
    {
        $category = $this->categoryModel->getCategoryById($categoryId);
        return $category ? $category['name'] : null;
    }

    private function handleImageUpload($key)
    {
        if (isset($_FILES['image']['tmp_name'][$key]) && $_FILES['image']['error'][$key] == UPLOAD_ERR_OK) {
            $uploadDirectory = './Uploads/images/';
            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0755, true);
            }

            $fileName = uniqid('image_') . '.' . pathinfo($_FILES['image']['name'][$key], PATHINFO_EXTENSION);
            $filePath = $uploadDirectory . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'][$key], $filePath)) {
                error_log("Uploaded image for product at index $key to $filePath.");
                return $filePath;
            }
            error_log("Failed to upload image at index $key.");
        }
        return null;
    }

    public function edit($id)
    {
        $purchase = $this->model->getPurchase($id);
        if (!$purchase) {
            $this->redirect('/purchase', 'Purchase not found.');
            return;
        }
        $categories = $this->categoryModel->getCategory();
        $this->views('purchase/edit', ['purchase' => $purchase, 'categories' => $categories]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("/purchase/edit/$id", 'Invalid request method.');
            return;
        }
        $purchase = $this->model->getPurchase($id);
        if (!$purchase) {
            $this->redirect('/purchase', 'Purchase not found.');
            return;
        }
        $data = $this->preparePurchaseData();
        $this->model->updatePurchase($id, $data);
        $this->redirect('/purchase', 'Purchase updated successfully.');
    }

    private function preparePurchaseData()
    {
        $data = [
            'product_name' => htmlspecialchars(trim($_POST['product_name'])),
            'category_id' => intval($_POST['category_id']),
            'barcode' => htmlspecialchars(trim($_POST['barcode'] ?? ''))
        ];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDirectory = './Uploads/images/';
            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0755, true);
            }
            $fileName = uniqid('image_') . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filePath = $uploadDirectory . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                $data['image'] = $filePath;
            }
        }

        return $data;
    }

    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/purchase', 'Invalid request method.');
            return;
        }
        if ($this->model->deletePurchase($id)) {
            $this->redirect('/purchase', 'Purchase deleted successfully.');
        } else {
            $this->redirect('/purchase', 'Failed to delete purchase.');
        }
    }

    public function bulkDestroy()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $ids = $input['ids'] ?? [];

        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'No items selected.']);
            return;
        }

        try {
            $this->model->startTransaction();
            $this->model->bulkDelete($ids);
            $this->model->commitTransaction();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $this->model->rollBackTransaction();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}