<?php
ob_start(); // Catch stray output
require_once './Models/InventoryModel.php';
require_once './Models/CategoryModel.php';

class InventoryController extends BaseController
{
    private $model;
    private $categories;

    public function __construct()
    {
        ini_set('display_errors', '0');
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

            // Get existing product details if product_id exists
            $existingImage = null;
            if (!empty($productIds[$index])) {
                $existingProduct = $this->model->getInventoryById($productIds[$index]);
                $existingImage = $existingProduct['image'] ?? null;
            }

            // Handle image upload or use existing image
            $imagePath = $this->handleImageUpload($index, $existingImage);
            if (!$imagePath && $existingImage) {
                $imagePath = $existingImage; // Fallback to existing image
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
        // Detect AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        error_log("Entering update: isAjax=$isAjax");

        // Clear output buffer to prevent stray output
        ob_clean();

        // Set JSON header early
        header('Content-Type: application/json', true);

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                error_log("Invalid request method for update: " . $_SERVER['REQUEST_METHOD']);
                http_response_code(405);
                echo json_encode(['error' => 'Invalid request method.']);
                exit;
            }

            error_log("Update POST: " . print_r($_POST, true));
            error_log("Update FILES: " . print_r($_FILES, true));
            error_log("Session CSRF Token: " . ($_SESSION['csrf_token'] ?? 'none'));
            error_log("Received CSRF Token: " . ($_POST['_token'] ?? 'none'));

            $id = $_POST['id'] ?? null;
            if (!$id || !is_numeric($id)) {
                error_log("Invalid or missing ID for update: " . ($id ?? 'null'));
                http_response_code(400);
                echo json_encode(['error' => 'Invalid item ID.']);
                exit;
            }

            if (!isset($_POST['_token']) || $_POST['_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                error_log("CSRF token validation failed for update id $id");
                http_response_code(403);
                echo json_encode(['error' => 'Invalid CSRF token.']);
                exit;
            }

            $inventory = $this->model->getInventoryById($id);
            if (!$inventory) {
                error_log("Inventory item not found for update: id = $id");

                echo json_encode(['error' => 'Inventory item not found.']);
                exit;
            }

            $productName = trim($_POST['product_name'] ?? '');
            if (empty($productName)) {
                error_log("Product name is empty for update id $id");
                http_response_code(400);
                echo json_encode(['error' => 'Product name is required.']);
                exit;
            }

            $categoryId = $_POST['category_id'] ?? null;
            $category = $categoryId ? $this->categories->getCategoryById($categoryId) : null;
            if ($categoryId && !$category) {
                error_log("Invalid category_id for update: " . ($categoryId ?? 'null'));
                http_response_code(400);
                echo json_encode(['error' => 'Invalid category selected.']);
                exit;
            }
            if (!$categoryId) {
                error_log("Category ID is required for update id $id");
                http_response_code(400);
                echo json_encode(['error' => 'Category is required.']);
                exit;
            }

            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : $inventory['quantity'];
            if ($quantity < 0) {
                error_log("Invalid quantity for update id $id: $quantity");
                http_response_code(400);
                echo json_encode(['error' => 'Quantity cannot be negative.']);
                exit;
            }

            $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : $inventory['amount'];
            if ($amount < 0) {
                error_log("Invalid amount for update id $id: $amount");
                http_response_code(400);
                echo json_encode(['error' => 'Amount cannot be negative.']);
                exit;
            }

            $sellingPrice = isset($_POST['selling_price']) ? (float)$_POST['selling_price'] : $inventory['selling_price'];
            if ($sellingPrice < 0) {
                error_log("Invalid selling price for update id $id: $sellingPrice");
                http_response_code(400);
                echo json_encode(['error' => 'Selling price cannot be negative.']);
                exit;
            }

            $imagePath = $inventory['image'];
            if (isset($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png'];
                $maxSize = 2 * 1024 * 1024;
                if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                    error_log("Invalid image type for update id $id: {$_FILES['image']['type']}");
                    http_response_code(400);
                    echo json_encode(['error' => 'Only JPEG or PNG images allowed.']);
                    exit;
                }
                if ($_FILES['image']['size'] > $maxSize) {
                    error_log("Image size exceeds 2MB for update id $id: {$_FILES['image']['size']}");
                    http_response_code(400);
                    echo json_encode(['error' => 'Image size must not exceed 2MB.']);
                    exit;
                }
                $imagePath = $this->handleImageUpload(null, $inventory['image']);
                if (!$imagePath) {
                    error_log("Image upload failed for update id $id");
                    http_response_code(400);
                    echo json_encode(['error' => 'Failed to upload image.']);
                    exit;
                }
            }
            error_log("Image path: " . ($imagePath ?? 'none'));

            $data = [
                'category_id' => $categoryId,
                'category_name' => $category['name'] ?? null,
                'product_name' => $productName,
                'quantity' => $quantity,
                'amount' => $amount,
                'selling_price' => $sellingPrice,
                'total_price' => $this->calculateTotalPrice($quantity, $amount),
                'expiration_date' => $_POST['expiration_date'] ?? $inventory['expiration_date'],
                'image' => $imagePath,
                'barcode' => $_POST['barcode'] ?? $inventory['barcode']
            ];

            error_log("Attempting to update inventory id=$id with data: " . json_encode($data));
            $updateResult = $this->model->updateInventory($id, $data);
            error_log("Update result for id=$id: " . json_encode($updateResult));

            if (isset($updateResult['success']) && $updateResult['success']) {
                error_log("Updated inventory id $id successfully.");
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Inventory updated successfully.']);
                exit;
            }

            $errorMessage = $updateResult['error'] ?? 'Failed to update inventory. Please try again.';
            error_log("Failed to update inventory id $id: $errorMessage");
            http_response_code(400);
            echo json_encode(['error' => $errorMessage]);
            exit;
        } catch (Exception $e) {
            error_log("Unexpected error in update id $id: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
            http_response_code(500);
            echo json_encode(['error' => 'Server error occurred. Please try again later.']);
            exit;
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
            header('Content-Type: application/json', true, 200);
            echo json_encode($inventory ?: ['error' => 'Product not found']);
            exit();
        }
        error_log("Invalid ID for getProductDetails.");
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
            header('Content-Type: application/json', true, 200);
            echo json_encode($inventory);
            exit();
        }
        error_log("Invalid barcode for getProductByBarcode.");
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
                return $existingImage;
            }
        }
        if (!is_writable($targetDir)) {
            error_log("Uploads directory is not writable");
            return $existingImage;
        }
    
        // Handle array-based image upload (for multiple rows)
        if ($index !== null && isset($_FILES['image']['name'][$index]) && $_FILES['image']['error'][$index] === UPLOAD_ERR_OK) {
            if (!in_array($_FILES['image']['type'][$index], $allowedTypes) || $_FILES['image']['size'][$index] > $maxSize) {
                error_log("Invalid file type or size at index $index");
                return $existingImage;
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
                return $existingImage;
            }
        } elseif ($index === null && isset($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if (!in_array($_FILES['image']['type'], $allowedTypes) || $_FILES['image']['size'] > $maxSize) {
                error_log("Invalid file type or size for single upload");
                return $existingImage;
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
                return $existingImage;
            }
        }
    
        // Return existing image if no new upload
        return $existingImage;
    }

    private function sendResponse($isAjax, $response, $redirectUrl, $statusCode = 200)
    {
        error_log("Sending response: status=$statusCode, response=" . json_encode($response));
        if ($isAjax) {
            header('Content-Type: application/json', true, $statusCode);
            echo json_encode($response);
            ob_end_flush();
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
