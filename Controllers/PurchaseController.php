<?php
require_once 'Models/PurchaseModel.php';
require_once 'BaseController.php';

class PurchaseController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new PurchaseModel();
    }

    // Show all purchases
    function index()
    {
        $purchases = $this->model->getPurchases();
        $this->views('purchase/list', ['purchases' => $purchases]);
    }

    // In PurchaseController.php
    public function getExistingProducts()
    {
        $inventoryModel = new InventoryModel();
        $categoryId = $_GET['category_id'] ?? null;

        $products = $inventoryModel->getInventoryWithCategory();
        if ($categoryId) {
            $products = array_filter($products, function ($item) use ($categoryId) {
                return $item['category_id'] == $categoryId;
            });
        }

        $response = array_map(function ($product) {
            return [
                'id' => $product['id'],
                'product_name' => $product['product_name'],
                'image' => $product['image'], // File path or adjust if binary
                'amount' => $product['amount'],
                'quantity' => $product['quantity'],
                'category_id' => $product['category_id']
            ];
        }, $products);

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    // Show the form to create a new purchase
    // In PurchaseController.php
    function create()
    {
        $categories = $this->model->getCategories();
        $inventoryModel = new InventoryModel();
        $inventoryItems = $inventoryModel->getInventoryWithCategory(); // Fetch all inventory items with category info

        $this->views('purchase/create', [
            'categories' => $categories,
            'inventoryItems' => $inventoryItems
        ]);
    }



    public function updateInline()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $id = $_POST['id'] ?? null;
        $field = $_POST['field'] ?? null;
        $value = $_POST['value'] ?? null;

        if (!$id || !$field || $value === null) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            return;
        }

        try {
            $this->model->updateField($id, $field, $value);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Add bulk destroy method
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


        $this->model->startTransaction();

        try {
            $product_names = $_POST['product_name'];
            $category_ids = $_POST['category_id'];
            $quantities = $_POST['quantity'];
            $amounts = $_POST['amount'];
            $type_of_products = $_POST['typeOfproducts'];

            foreach ($category_ids as $category_id) {
                if (!$this->model->categoryExists($category_id)) {
                    $this->model->rollBackTransaction();
                    $this->redirect('/purchase/create', 'Invalid category!');
                    return;
                }
            }

            foreach ($product_names as $index => $product_name) {
                if (
                    empty($product_name) || !is_numeric($category_ids[$index]) ||
                    !is_numeric($quantities[$index]) || !is_numeric($amounts[$index])
                ) {
                    $this->model->rollBackTransaction();
                    $this->redirect('/purchase/create', "Invalid input at index $index!");
                    return;
                }

                // Handle image upload
                $imageData = null;
                if (
                    isset($_FILES['image']['tmp_name'][$index]) &&
                    is_uploaded_file($_FILES['image']['tmp_name'][$index])
                ) {
                    $imageData = file_get_contents($_FILES['image']['tmp_name'][$index]);
                    if ($imageData === false) {
                        $this->model->rollBackTransaction();
                        $this->redirect('/purchase/create', "Failed to read image at index $index!");
                        return;
                    }
                }

                $this->model->insertProduct(
                    $product_name,
                    $category_ids[$index],
                    $quantities[$index],
                    $amounts[$index],
                    $type_of_products[$index],
                    $imageData
                );
            }

            $this->model->commitTransaction();
            $this->redirect('/purchase', 'Purchase added successfully!');
        } catch (Exception $e) {
            $this->model->rollBackTransaction();
            $this->redirect('/purchase/create', 'Error: ' . $e->getMessage());
        }
    }

    function edit($id)
    {
        $purchase = $this->model->getPurchase($id);
        if (!$purchase) {
            $this->redirect('/purchase', 'Purchase not found.');
            return;
        }

        $categories = $this->model->getCategories();
        $this->views('purchase/edit', ['purchase' => $purchase, 'categories' => $categories]);
    }

    function update($id)
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

        $imageData = $purchase['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            if ($imageData === false) {
                $this->redirect("/purchase/edit/$id", 'Failed to read image.');
                return;
            }
        }

        $data = [
            'product_name' => htmlspecialchars($_POST['product_name']),
            'category_id' => intval($_POST['category_id']),
            'quantity' => intval($_POST['quantity']),
            'price' => floatval($_POST['price']),
            'type_of_product' => htmlspecialchars($_POST['type_of_product']),
            'image' => $imageData
        ];

        try {
            $this->model->updatePurchase($id, $data);
            $this->redirect('/purchase', 'Purchase updated successfully!');
        } catch (Exception $e) {
            $this->redirect("/purchase/edit/$id", 'Error: ' . $e->getMessage());
        }
    }
    public function store()
    {
        if (!isset($_POST['product_name'], $_POST['category_id'], $_POST['quantity'], $_POST['amount'], $_POST['typeOfproducts'], $_POST['expiration_date'])) {
            $this->redirect('/purchase/create', 'Missing data!');
            return;
        }

        $this->model->startTransaction();
        $inventoryModel = new InventoryModel();
        $categoryModel = new CategoryModel();

        try {
            $product_names = $_POST['product_name'];
            $category_ids = $_POST['category_id'];
            $quantities = $_POST['quantity'];
            $amounts = $_POST['amount'];
            $type_of_products = $_POST['typeOfproducts'];
            $expiration_dates = $_POST['expiration_date']; // Get expiration dates
            $inventory_ids = $_POST['inventory_id'] ?? []; // Get inventory IDs

            foreach ($product_names as $index => $product_name) {
                $category_id = $category_ids[$index];
                $quantity = (int)$quantities[$index];
                $amount = (float)$amounts[$index];
                $type = $type_of_products[$index];
                $expiration_date = $expiration_dates[$index]; // Add expiration date

                // Initialize imageData to null
                $imageData = null;

                if ($type === 'New') {
                    // Check if an image file was uploaded
                    if (isset($_FILES['image']['tmp_name'][$index]) && is_uploaded_file($_FILES['image']['tmp_name'][$index])) {
                        $imageData = file_get_contents($_FILES['image']['tmp_name'][$index]); // Get image data
                    }
                } elseif ($type === 'Old') {
                    $inventory_id = $inventory_ids[$index] ?? null; // Get inventory ID for the current index
                    if ($inventory_id) {
                        $currentItem = $inventoryModel->getInventorys($inventory_id);
                        if ($currentItem) {
                            $imageData = $currentItem['image'];
                        }
                    }
                }

                // Validate inputs
                if (!$this->model->categoryExists($category_id) || empty($product_name) || $quantity <= 0 || $amount < 0) {
                    $this->model->rollBackTransaction();
                    $this->redirect('/purchase/create', "Invalid input at index $index!");
                    return;
                }

                $category = $categoryModel->getCategoryById($category_id);
                $category_name = $category['name'] ?? '';

                if ($type === 'New') {
                    // Insert into purchase table
                    $purchaseId = $this->model->insertProduct($product_name, $category_id, $quantity, $amount, $type, $expiration_date, $imageData);

                    // Insert into inventory
                    $inventoryModel->insertInventory([
                        'product_name' => $product_name,
                        'category_id' => $category_id,
                        'quantity' => $quantity,
                        'amount' => $amount,
                        'image' => $imageData, // Ensure image data is included
                        'category_name' => $category_name,
                        'expiration_date' => $expiration_date,
                        'total_price' => $quantity * $amount
                    ]);
                } elseif ($type === 'Old' && $inventory_id) {
                    // For "Old" products: Only update inventory, skip purchase table
                    if ($currentItem) {
                        $newQuantity = (int)$currentItem['quantity'] + (int)$quantity;
                        $inventoryModel->updateInventoryQuantity($inventory_id, $newQuantity, $amount);
                        error_log("Updated inventory ID $inventory_id with quantity $newQuantity");
                    } else {
                        $this->model->rollBackTransaction();
                        $this->redirect('/purchase/create', "Inventory item not found for ID: $inventory_id!");
                        return;
                    }
                }
            }

            $this->model->commitTransaction();
            $this->redirect('/purchase', 'Purchase added and inventory updated successfully!');
        } catch (Exception $e) {
            $this->model->rollBackTransaction();
            $this->redirect('/purchase/create', 'Error: ' . $e->getMessage());
        }
    }
    // Delete purchase
    // In PurchaseController.php
    function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/purchase', 'Invalid request method.');
            return;
        }

        $purchase = $this->model->getPurchase($id);
        if (!$purchase) {
            $this->redirect('/purchase', 'Purchase not found.');
            return;
        }

        try {
            $this->model->deletePurchase($id);
            $this->redirect('/purchase', 'Purchase deleted successfully!');
        } catch (Exception $e) {
            $this->redirect('/purchase', 'Error deleting purchase: ' . $e->getMessage());
        }
    }
    private function uploadImage($file, $index): ?string
    {
        if (!isset($file['tmp_name'][$index]) || !is_uploaded_file($file['tmp_name'][$index])) {
            return null;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $mimeType = mime_content_type($file['tmp_name'][$index]);
        if (!in_array($mimeType, $allowedTypes) || $file['size'][$index] > 2 * 1024 * 1024) {
            throw new Exception("Invalid file type or size at index $index");
        }

        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $imageName = uniqid() . '-' . basename($file['name'][$index]);
        $targetPath = $targetDir . $imageName;

        if (move_uploaded_file($file['tmp_name'][$index], $targetPath)) {
            return $targetPath;
        }
        throw new Exception("Failed to upload image at index $index");
    }
    // Handle image upload
    private function handleImageUpload()
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileMimeType = mime_content_type($_FILES['image']['tmp_name']);

            if (!in_array($fileMimeType, $allowedMimeTypes)) {
                $this->redirect($_SERVER['HTTP_REFERER'], 'Invalid file type! Only JPG, PNG, and GIF allowed.');
                return null;
            }

            $uploadDir = __DIR__ . '/../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $newImagePath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $newImagePath)) {
                return $newImagePath; // Return the new image path
            } else {
                $this->redirect($_SERVER['HTTP_REFERER'], 'Image upload failed.');
            }
        }
        return null; // Return null if no image is uploaded
    }

    // Redirect function with message
    public function redirect($url, $message = '')
    {
        if ($message) {
            $_SESSION['message'] = $message;
        }
        header("Location: $url");
        exit();
    }
}
