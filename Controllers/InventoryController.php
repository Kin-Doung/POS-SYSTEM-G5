<?php
// Start output buffering at the top
ob_start();
require_once './Models/InventoryModel.php';
require_once './Models/CategoryModel.php';

class InventoryController extends BaseController
{
    private $model;
    private $categories;

    public function __construct()
    {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Generate CSRF token if not set
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        // Disable error display, log errors instead
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
        $this->model = new InventoryModel();
        $this->categories = new CategoryModel();
    }

    public function index()
    {
        $inventory = $this->model->getInventory();
        $categories = $this->categories->getCategory();
        foreach ($inventory as &$item) {
            $item['selling_price'] = isset($item['selling_price']) ? floatval($item['selling_price']) : 0.00;
        }
        unset($item);
        $this->views('inventory/list', compact('inventory', 'categories'));
    }

    public function create()
    {
        $categories = $this->categories->getCategory();
        $inventory = $this->model->getInventory();
        if (empty($inventory)) {
            error_log("No inventory items found for create form.");
        }
        $this->views('inventory/create', compact('categories', 'inventory'));
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Invalid request method for store.");
            ob_end_clean();
            header('Content-Type: application/json', true, 405);
            echo json_encode(['error' => 'Invalid request method']);
            exit();
        }

        $productIds = $_POST['product_id'] ?? [];
        $productNames = $_POST['product_name_text'] ?? $_POST['product_name'] ?? [];
        $quantities = $_POST['quantity'] ?? [];
        $amounts = $_POST['amount'] ?? [];
        $sellingPrices = $_POST['selling_price'] ?? [];
        $barcodes = $_POST['barcode'] ?? [];
        $categoryIds = $_POST['category_id'] ?? [];
        $categoryNames = $_POST['category_name'] ?? [];
        $expirationDates = $_POST['expiration_date'] ?? [];
        $images = $_FILES['image'] ?? [];

        $errors = [];
        $successIds = [];

        foreach ($productNames as $index => $productName) {
            if (empty($productName) && empty($productIds[$index])) {
                $errors[] = "Product name or ID missing at index $index";
                continue;
            }

            $existingImage = null;
            if (!empty($productIds[$index])) {
                $existingProduct = $this->model->getInventoryById($productIds[$index]);
                $existingImage = $existingProduct['image'] ?? null;
            }

            $imagePath = $this->handleImageUpload($index, $existingImage);
            if (!$imagePath && $existingImage) {
                $imagePath = $existingImage;
            }

            $quantity = isset($quantities[$index]) ? (int)$quantities[$index] : 0;
            $amount = isset($amounts[$index]) ? (float)$amounts[$index] : 0;
            $data = [
                'image' => $imagePath,
                'product_id' => $productIds[$index] ?? null,
                'product_name' => $productName,
                'quantity' => $quantity,
                'amount' => $amount,
                'selling_price' => isset($sellingPrices[$index]) ? (float)$sellingPrices[$index] : 0,
                'total_price' => $quantity * $amount,
                'category_id' => $categoryIds[$index] ?? null,
                'category_name' => $categoryNames[$index] ?? null,
                'barcode' => $barcodes[$index] ?? null,
                'expiration_date' => $expirationDates[$index] ?? null
            ];
            $result = $this->model->store($data);
            if (isset($result['error'])) {
                $errors[] = "Failed to update stock for $productName: {$result['error']}";
            } else {
                $successIds[] = $result['id'];
                error_log("Updated stock for $productName (ID: {$result['id']}), added quantity: $quantity, image: $imagePath");
            }
        }

        if (empty($errors)) {
            $_SESSION['success'] = "Items added/updated successfully.";
        } else {
            $_SESSION['error'] = implode(", ", $errors);
        }
        $this->redirect('/inventory');
    }

    public function edit($id)
    {
        $inventory = $this->model->getInventoryById($id);
        $categories = $this->categories->getCategory();
        if (!$inventory) {
            error_log("Inventory item not found for edit: id = $id");
            $_SESSION['error'] = "Inventory item not found.";
            $this->redirect('/inventory');
        }
        $inventory['selling_price'] = isset($inventory['selling_price']) ? floatval($inventory['selling_price']) : 0.00;
        $inventory['amount'] = isset($inventory['amount']) ? floatval($inventory['amount']) : 0.00;
        $this->views('inventory/edit', compact('inventory', 'categories'));
    }

    public function update()
    {
        // Log entry to confirm endpoint is reached
        error_log("Reached /inventory/update");

        // Detect AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Clear all output buffers
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        ob_start();

        // Set JSON header for AJAX
        if ($isAjax) {
            header('Content-Type: application/json', true);
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                error_log("Invalid request method for update: " . $_SERVER['REQUEST_METHOD']);
                $this->sendResponse($isAjax, ['error' => 'Invalid request method.'], '/inventory', 405);
            }

            error_log("Update POST: " . print_r($_POST, true));
            error_log("Update FILES: " . print_r($_FILES, true));
            error_log("Session CSRF Token: " . ($_SESSION['csrf_token'] ?? 'none'));
            error_log("Received CSRF Token: " . ($_POST['_token'] ?? 'none'));

            // Validate CSRF token
            if (!isset($_POST['_token']) || $_POST['_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                error_log("CSRF token validation failed");
                $this->sendResponse($isAjax, ['error' => 'Invalid CSRF token. Please refresh the page.'], '/inventory', 403);
            }

            // Validate ID
            $id = $_POST['id'] ?? null;
            if (!$id || !is_numeric($id)) {
                error_log("Invalid or missing ID: " . ($id ?? 'null'));
                $this->sendResponse($isAjax, ['error' => 'Invalid item ID.'], '/inventory', 400);
            }

            // Check if item exists
            $inventory = $this->model->getInventoryById($id);
            if (!$inventory) {
                error_log("Inventory item not found: id = $id");
                $this->sendResponse($isAjax, ['error' => 'Inventory item not found.'], '/inventory', 404);
            }

            // Validate required fields
            $productName = trim($_POST['product_name'] ?? '');
            if (empty($productName)) {
                error_log("Product name is empty for id $id");
                $this->sendResponse($isAjax, ['error' => 'Product name is required.'], '/inventory', 400);
            }

            $categoryId = $_POST['category_id'] ?? null;
            if (!$categoryId) {
                error_log("Category ID is required for id $id");
                $this->sendResponse($isAjax, ['error' => 'Category is required.'], '/inventory', 400);
            }

            $category = $this->categories->getCategoryById($categoryId);
            if (!$category) {
                error_log("Invalid category_id: " . ($categoryId ?? 'null'));
                $this->sendResponse($isAjax, ['error' => 'Invalid category selected.'], '/inventory', 400);
            }

            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : $inventory['quantity'];
            if ($quantity < 0) {
                error_log("Invalid quantity for id $id: $quantity");
                $this->sendResponse($isAjax, ['error' => 'Quantity cannot be negative.'], '/inventory', 400);
            }

            $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : $inventory['amount'];
            if ($amount < 0) {
                error_log("Invalid amount for id $id: $amount");
                $this->sendResponse($isAjax, ['error' => 'Amount cannot be negative.'], '/inventory', 400);
            }

            $sellingPrice = isset($_POST['selling_price']) ? (float)$_POST['selling_price'] : ($inventory['selling_price'] ?? 0);
            if ($sellingPrice < 0) {
                error_log("Invalid selling price for id $id: $sellingPrice");
                $this->sendResponse($isAjax, ['error' => 'Selling price cannot be negative.'], '/inventory', 400);
            }

            // Validate optional fields
            $barcode = trim($_POST['barcode'] ?? $inventory['barcode'] ?? '');
            if ($barcode && strlen($barcode) > 50) {
                error_log("Barcode exceeds maximum length for id $id: $barcode");
                $this->sendResponse($isAjax, ['error' => 'Barcode exceeds maximum length of 50 characters.'], '/inventory', 400);
            }

            $expirationDate = $_POST['expiration_date'] ?? $inventory['expiration_date'] ?? null;
            if ($expirationDate && !strtotime($expirationDate)) {
                error_log("Invalid expiration date for id $id: $expirationDate");
                $this->sendResponse($isAjax, ['error' => 'Invalid expiration date.'], '/inventory', 400);
            }

            // Handle image upload
            $imagePath = $inventory['image'] ?? null;
            if (isset($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload(null, $inventory['image']);
                if (!$imagePath) {
                    error_log("Image upload failed for id $id");
                    $this->sendResponse($isAjax, ['error' => 'Failed to upload image.'], '/inventory', 400);
                }
            }

            // Prepare data for update
            $data = [
                'category_id' => $categoryId,
                'category_name' => $category['name'] ?? null,
                'product_name' => $productName,
                'quantity' => $quantity,
                'amount' => $amount,
                'selling_price' => $sellingPrice,
                'total_price' => $this->calculateTotalPrice($quantity, $amount),
                'expiration_date' => $expirationDate,
                'image' => $imagePath,
                'barcode' => $barcode
            ];

            error_log("Attempting to update inventory id=$id with data: " . json_encode($data));

            // Perform update
            $updateResult = $this->model->updateInventory($id, $data);
            if (isset($updateResult['success']) && $updateResult['success']) {
                error_log("Updated inventory id $id successfully");
                $this->sendResponse($isAjax, ['success' => true, 'message' => 'Inventory updated successfully.'], '/inventory', 200);
            }

            $errorMessage = $updateResult['error'] ?? 'Failed to update inventory.';
            error_log("Failed to update inventory id $id: $errorMessage");
            $this->sendResponse($isAjax, ['error' => $errorMessage], '/inventory', 400);
        } catch (Exception $e) {
            error_log("Unexpected error in update id $id: " . $e->getMessage());
            $this->sendResponse($isAjax, ['error' => 'Server error occurred. Please try again later.'], '/inventory', 500);
        }
    }

    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
            $_SESSION['error'] = "Invalid request method.";
            $this->redirect('/inventory');
        }

        if (!isset($_POST['_token']) || $_POST['_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            error_log("CSRF token validation failed for destroy");
            $_SESSION['error'] = "Invalid CSRF token.";
            $this->redirect('/inventory');
        }

        $id = $_POST['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            error_log("Invalid or missing ID: " . var_export($id, true));
            $_SESSION['error'] = "Invalid item ID.";
            $this->redirect('/inventory');
        }

        $result = $this->model->destroy((int)$id);
        if (isset($result['error'])) {
            $_SESSION['error'] = $result['error'];
        } else {
            $_SESSION['success'] = "Item deleted successfully.";
        }
        $this->redirect('/inventory');
    }

    public function view()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            error_log("Invalid ID for view.");
            $_SESSION['error'] = "Invalid ID provided.";
            $this->redirect('/inventory');
        }
        $inventory = $this->model->viewInventory($_GET['id']);
        if (!$inventory) {
            error_log("Inventory item not found for view: id = " . $_GET['id']);
            $_SESSION['error'] = "Inventory item not found.";
            $this->redirect('/inventory');
        }
        $this->views('inventory/view', compact('inventory'));
    }

    public function getProductDetails()
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $inventory = $this->model->getInventoryById($_GET['id']);
            ob_end_clean();
            header('Content-Type: application/json', true, 200);
            echo json_encode($inventory ?: ['error' => 'Product not found']);
            exit();
        }
        error_log("Invalid ID for getProductDetails.");
        ob_end_clean();
        header('Content-Type: application/json', true, 400);
        echo json_encode(['error' => 'Invalid ID']);
        exit();
    }

    public function getProductByBarcode()
    {
        error_log("Received request for barcode: " . ($_GET['barcode'] ?? 'none'));
        if (isset($_GET['barcode']) && !empty($_GET['barcode'])) {
            $barcode = trim($_GET['barcode']);
            $inventory = $this->model->getProductByBarcode($barcode);
            ob_end_clean();
            header('Content-Type: application/json', true, 200);
            echo json_encode($inventory);
            exit();
        }
        error_log("Invalid barcode for getProductByBarcode.");
        ob_end_clean();
        header('Content-Type: application/json', true, 400);
        echo json_encode(['error' => 'Invalid barcode']);
        exit();
    }

    private function calculateTotalPrice($quantity, $amount)
    {
        return $quantity * $amount;
    }

    private function handleImageUpload($index = null, $existingImage = null)
    {
        $allowedTypes = ['image/jpeg', 'image/png'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        $targetDir = "Uploads/";

        // Ensure directory exists and is writable
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0755, true)) {
                error_log("Failed to create Uploads directory");
                throw new Exception("Cannot create upload directory.");
            }
        }
        if (!is_writable($targetDir)) {
            error_log("Uploads directory is not writable");
            throw new Exception("Upload directory is not writable.");
        }

        // Handle array-based image upload
        if ($index !== null && isset($_FILES['image']['name'][$index]) && $_FILES['image']['error'][$index] === UPLOAD_ERR_OK) {
            if (!in_array($_FILES['image']['type'][$index], $allowedTypes) || $_FILES['image']['size'][$index] > $maxSize) {
                error_log("Invalid file type or size at index $index");
                throw new Exception("Invalid image type or size. Only JPEG/PNG under 2MB allowed.");
            }
            $imagePath = $targetDir . uniqid() . '-' . basename($_FILES['image']['name'][$index]);
            if (move_uploaded_file($_FILES['image']['tmp_name'][$index], $imagePath)) {
                if ($existingImage && file_exists($existingImage)) {
                    unlink($existingImage);
                }
                error_log("Successfully uploaded image to $imagePath");
                return $imagePath;
            } else {
                error_log("Failed to move uploaded file to $imagePath");
                throw new Exception("Failed to move uploaded file.");
            }
        } elseif ($index === null && isset($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if (!in_array($_FILES['image']['type'], $allowedTypes) || $_FILES['image']['size'] > $maxSize) {
                error_log("Invalid file type or size for single upload");
                throw new Exception("Invalid image type or size. Only JPEG/PNG under 2MB allowed.");
            }
            $imagePath = $targetDir . uniqid() . '-' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                if ($existingImage && file_exists($existingImage)) {
                    unlink($existingImage);
                }
                error_log("Successfully uploaded image to $imagePath");
                return $imagePath;
            } else {
                error_log("Failed to move uploaded file to $imagePath");
                throw new Exception("Failed to move uploaded file.");
            }
        }

        return $existingImage;
    }

    private function sendResponse($isAjax, $response, $redirectUrl, $statusCode = 200)
    {
        error_log("Sending response: status=$statusCode, response=" . json_encode($response));
        // Log buffered output for debugging
        $bufferedOutput = ob_get_contents();
        if ($bufferedOutput) {
            error_log("Unexpected buffered output: " . $bufferedOutput);
        }
        // Clear all buffers
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        if ($isAjax) {
            http_response_code($statusCode);
            echo json_encode($response);
            exit();
        }
        if (isset($response['error'])) {
            $_SESSION['error'] = $response['error'];
        } elseif (isset($response['message'])) {
            $_SESSION['success'] = $response['message'];
        }
        $this->redirect($redirectUrl);
    }
}