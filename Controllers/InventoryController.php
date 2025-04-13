<?php
require_once './Models/InventoryModel.php';
require_once './Models/CategoryModel.php';

class InventoryController extends BaseController
{
    private $model;
    private $categories;

    public function __construct()
    {
        $this->model = new InventoryModel();
        $this->categories = new CategoryModel();
    }

    public function index()
    {
        $inventory = $this->model->getInventory();
        $categories = $this->model->getCategory();
        foreach ($inventory as &$item) {
            if ($item['image']) {
                $item['image_base64'] = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($item['image']));
            }
            $item['selling_price'] = isset($item['selling_price']) ? $item['selling_price'] : 0.00;
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
            die("Invalid request method.");
        }

        $categoryIds = $_POST['category_id'] ?? [];
        $productIds = $_POST['product_name'] ?? [];
        $quantities = $_POST['quantity'] ?? [];
        $prices = $_POST['amount'] ?? [];
        $sellingPrices = $_POST['selling_price'] ?? [];

        if (empty($productIds) || count($productIds) !== count($quantities)) {
            error_log("Invalid form data: " . print_r($_POST, true));
            die("Error: Please fill all required fields.");
        }

        foreach ($productIds as $index => $productId) {
            if (empty($productId) || !is_numeric($productId)) {
                error_log("Invalid product_id at index $index: " . ($productId ?? 'null'));
                continue;
            }
            $quantity = isset($quantities[$index]) ? (int)$quantities[$index] : 0;
            if ($quantity <= 0) {
                error_log("Invalid quantity at index $index: $quantity");
                continue;
            }

            $categoryId = $categoryIds[$index] ?? null;
            $amount = isset($prices[$index]) ? (float)$prices[$index] : 0.00;
            $sellingPrice = isset($sellingPrices[$index]) ? (float)$sellingPrices[$index] : 0.00;

            $category = $this->categories->getCategoryById($categoryId);
            if (!$category) {
                error_log("Invalid category_id at index $index: " . ($categoryId ?? 'null'));
                continue;
            }

            $imagePath = $this->handleImageUpload($index);

            $existingInventory = $this->model->getInventoryById($productId);
            if ($existingInventory === false) {
                error_log("Failed to fetch inventory for product_id $productId");
                continue;
            }

            if ($existingInventory) {
                $newQuantity = $existingInventory['quantity'] + $quantity;
                $data = [
                    'product_name' => $existingInventory['product_name'],
                    'category_id' => $categoryId,
                    'category_name' => $category['name'],
                    'quantity' => $newQuantity,
                    'amount' => $amount,
                    'selling_price' => $sellingPrice,
                    'total_price' => $this->calculateTotalPrice($newQuantity, $amount),
                    'expiration_date' => $existingInventory['expiration_date'] ?? null,
                    'image' => $imagePath ?: $existingInventory['image'],
                ];
                if ($this->model->updateInventory($productId, $data)) {
                    error_log("Updated product_id $productId: new quantity = $newQuantity");
                } else {
                    error_log("Failed to update product_id $productId");
                }
            } else {
                $inventory = $this->model->getInventory();
                $product = array_filter($inventory, fn($p) => $p['id'] == $productId);
                $productName = $product ? reset($product)['product_name'] : 'Unknown Product';

                $data = [
                    'product_name' => $productName,
                    'category_id' => $categoryId,
                    'category_name' => $category['name'],
                    'quantity' => $quantity,
                    'amount' => $amount,
                    'selling_price' => $sellingPrice,
                    'total_price' => $this->calculateTotalPrice($quantity, $amount),
                    'expiration_date' => null,
                    'image' => $imagePath,
                ];
                try {
                    $newId = $this->model->createInventory($data);
                    error_log("Created new product: id = $newId, name = $productName");
                } catch (Exception $e) {
                    error_log("Failed to create product at index $index: " . $e->getMessage());
                }
            }
        }

        $this->redirect('/inventory');
    }

    public function edit($id)
    {
        $inventory = $this->model->getInventoryById($id);
        $categories = $this->categories->getCategory();
        if (!$inventory) {
            error_log("Inventory item not found for edit: id = $id");
            die("Inventory item not found.");
        }
        $this->views('inventory/edit', compact('inventory', 'categories'));
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventory = $this->model->getInventoryById($id);
            if (!$inventory) {
                error_log("Inventory item not found for update: id = $id");
                die("Inventory item not found.");
            }
            $imagePath = $this->handleImageUpload(null, $inventory['image']);

            $categoryId = $_POST['category_id'] ?? null;
            $category = $this->categories->getCategoryById($categoryId);
            if (!$category) {
                error_log("Invalid category_id for update: " . ($categoryId ?? 'null'));
                die("Invalid category selected.");
            }

            $data = [
                'category_id' => $categoryId,
                'category_name' => $category['name'],
                'product_name' => $_POST['product_name'] ?? $inventory['product_name'],
                'quantity' => isset($_POST['quantity']) ? (int)$_POST['quantity'] : $inventory['quantity'],
                'amount' => isset($_POST['amount']) ? (float)$_POST['amount'] : $inventory['amount'],
                'selling_price' => isset($_POST['selling_price']) ? (float)$_POST['selling_price'] : $inventory['selling_price'],
                'total_price' => $this->calculateTotalPrice(
                    isset($_POST['quantity']) ? $_POST['quantity'] : $inventory['quantity'],
                    isset($_POST['amount']) ? $_POST['amount'] : $inventory['amount']
                ),
                'expiration_date' => $_POST['expiration_date'] ?? $inventory['expiration_date'],
                'image' => $imagePath,
            ];
            if ($this->model->updateInventory($id, $data)) {
                error_log("Updated inventory id $id successfully.");
            } else {
                error_log("Failed to update inventory id $id.");
            }
            $this->redirect('/inventory');
        }
    }

    public function destroy()
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = $_GET['id'];
            if ($this->model->deleteItem($id)) {
                error_log("Deleted inventory id $id successfully.");
            } else {
                error_log("Failed to delete inventory id $id.");
            }
            $this->redirect('/inventory');
        } else {
            error_log("Invalid ID for destroy.");
            die("Invalid ID");
        }
    }

    public function view()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            error_log("Invalid ID for view.");
            die("Invalid ID provided.");
        }
        $inventory = $this->model->viewInventory($_GET['id']);
        if (!$inventory) {
            error_log("Inventory item not found for view: id = " . $_GET['id']);
            die("Inventory item not found.");
        }
        $this->views('inventory/view', compact('inventory'));
    }

    public function getProductDetails()
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $inventory = $this->model->getInventoryById($_GET['id']);
            header('Content-Type: application/json');
            echo json_encode($inventory ?: ['error' => 'Product not found']);
            exit();
        }
        error_log("Invalid ID for getProductDetails.");
        echo json_encode(['error' => 'Invalid ID']);
        exit();
    }

    private function calculateTotalPrice($quantity, $amount)
    {
        return $quantity * $amount;
    }

    private function handleImageUpload($index = null, $existingImage = null)
    {
        if ($index !== null && isset($_FILES['image']['name'][$index]) && $_FILES['image']['error'][$index] === UPLOAD_ERR_OK) {
            $targetDir = "Uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $imagePath = $targetDir . uniqid() . '-' . basename($_FILES['image']['name'][$index]);
            if (move_uploaded_file($_FILES['image']['tmp_name'][$index], $imagePath)) {
                return $imagePath;
            } else {
                error_log("Failed to move uploaded file to $imagePath");
            }
        } elseif ($index === null && isset($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "Uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $imagePath = $targetDir . uniqid() . '-' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                return $imagePath;
            } else {
                error_log("Failed to move uploaded file to $imagePath");
            }
        }
        return $existingImage;
    }
}