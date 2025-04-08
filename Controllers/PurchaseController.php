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

        // Show all purchases
        public function index()
        {
            $purchases = $this->model->getPurchases();
            $this->views('purchase/list', ['purchases' => $purchases]);
        }

        // Show the form to create a new purchase
        public function create()
        {
            $categories = $this->categoryModel->getCategory();
            $inventoryItems = $this->inventoryModel->getInventoryWithCategory();

            $this->views('purchase/create', [
                'categories' => $categories,
                'inventoryItems' => $inventoryItems
            ]);
        }

        // Store new purchases
        public function store()
        {
            try {
                if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                    throw new Exception('Invalid request method.');
                }

                $products = $this->processProductData();
                if (empty($products)) {
                    throw new Exception('No valid products to insert');
                }

                // Start transaction
                $this->model->startTransaction();

                // Insert products into purchase table
                $this->model->insertProducts($products);

                // Update inventory for each product
                foreach ($products as $product) {
                    $this->updateInventory($product);
                }

                // Commit transaction
                $this->model->commitTransaction();
                $this->redirect('/purchase', 'Products successfully added and inventory updated.');
            } catch (Exception $e) {
                // Roll back only if transaction is active

                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        // Helper method to update inventory
        private function updateInventory($product)
        {
            // Check if the product already exists in inventory
            $existingItem = $this->inventoryModel->getInventoryItemByProduct($product['product_name'], $product['category_id']);

            if ($existingItem) {
                // Update existing inventory (e.g., increment quantity)
                $newQuantity = $existingItem['quantity'] + 1; // Assuming 1 unit per purchase; adjust as needed
                $this->inventoryModel->updateInventory($existingItem['id'], [
                    'quantity' => $newQuantity
                ]);
            } else {
                // Add new item to inventory
                $this->inventoryModel->addToInventory([
                    'product_name' => $product['product_name'],
                    'category_id' => $product['category_id'],
                    'quantity' => 0, // Default quantity; adjust as needed
                    'image' => $product['image'] ?? null
                ]);
            }
        }

        // Process and validate product data
        private function processProductData()
        {
            $products = [];
            foreach ($_POST['product_name'] as $key => $productName) {
                $categoryId = $_POST['category_id'][$key];
                $categoryName = $this->getCategoryNameById($categoryId);

                if (!$categoryName) {
                    echo json_encode(['success' => false, 'message' => "Category with ID $categoryId not found"]);
                    return [];
                }

                $productName = htmlspecialchars(trim($productName)); // Sanitize product name
                $image = $this->handleImageUpload($key); // Handle image upload

                $products[] = [
                    'product_name' => $productName,
                    'category_id' => $categoryId,
                    'category_name' => $categoryName,
                    'image' => $image
                ];
            }

            return $products;
        }

        // Fetch category name by ID
        private function getCategoryNameById($categoryId)
        {
            $category = $this->categoryModel->getCategoryById($categoryId);
            return $category ? $category['name'] : null;
        }

        // Handle image upload and return the file path
        private function handleImageUpload($key)
        {
            if (isset($_FILES['image']['tmp_name'][$key]) && $_FILES['image']['error'][$key] == UPLOAD_ERR_OK) {
                $uploadDirectory = './uploads/images/'; // Define your image upload directory
                if (!is_dir($uploadDirectory)) {
                    mkdir($uploadDirectory, 0755, true); // Create the directory if it doesn't exist
                }

                $fileName = uniqid('image_') . '.' . pathinfo($_FILES['image']['name'][$key], PATHINFO_EXTENSION);
                $filePath = $uploadDirectory . $fileName;

                // Move the uploaded file to the desired directory
                if (move_uploaded_file($_FILES['image']['tmp_name'][$key], $filePath)) {
                    return $filePath; // Store the file path in the database
                }
            }
            return null; // Return null if no image uploaded
        }


        public function edit($id) {
            $purchase = $this->model->getPurchase($id);
            if (!$purchase) {
                $this->redirect('/purchase', 'Purchase not found.');
                return;
            }
            $categories = $this->categoryModel->getCategory();
            $this->views('purchase/edit', ['purchase' => $purchase, 'categories' => $categories]);
        }

        public function update($id) {
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
            if (isset($data['image'])) {
                $this->model->updatePurchase($id, $data);
            } else {
                unset($data['image']);
                $this->model->updatePurchase($id, $data);
            }
            $this->redirect('/purchase', 'Purchase updated successfully!');
        }


        // Prepare data for updating purchase (including image)
        private function preparePurchaseData()
        {
            $data = [
                'product_name' => htmlspecialchars($_POST['product_name']),
                'category_id' => intval($_POST['category_id']),
            ];

            // Only set image if uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = file_get_contents($_FILES['image']['tmp_name']);
            }

            return $data;
        }

        public function destroy($id) {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->redirect('/purchase', 'Invalid request method.');
                return;
            }
            if ($this->model->deletePurchase($id)) {
                $this->redirect('/purchase', 'Purchase deleted successfully!');
            } else {
                $this->redirect('/purchase', 'Failed to delete purchase.');
            }
        }
        // Bulk delete purchases
        public function bulkDestroy()
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                return;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            $ids = $input['ids'] ?? [];

            if (empty($ids)) {
                echo json_encode(['success' => false, 'message' => 'No items selected']);
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
