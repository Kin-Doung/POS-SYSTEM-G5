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

    /**
     * Display the inventory list
     */
    public function index()
    {
        $inventory = $this->model->getInventory();
        $categories = $this->model->getCategory();

        // Process images for display
        foreach ($inventory as &$item) {
            if ($item['image']) {
                $item['image_base64'] = 'data:image/jpeg;base64,' . base64_encode($item['image']);
            }
        }
        unset($item);

        $this->views('inventory/list', compact('inventory', 'categories'));
    }

    /**
     * Show form to create new inventory item
     */
    public function create()
    {
        $categories = $this->categories->getCategory();
        $inventory = $this->model->getInventory();
        if (empty($inventory)) {
            // Log the issue and show a user-friendly message
            error_log("Failed to load inventory for create form.");
            die("Error: Unable to load inventory data. Please try again later.");
        }
        $this->views('inventory/create', compact('categories', 'inventory'));
    }

    /**
     * Store a new inventory item
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryIds = $_POST['category_id'];
            $productIds = $_POST['product_name']; // Inventory ID from dropdown
            $quantities = $_POST['quantity'];
            $prices = $_POST['amount'];
            $expirationDates = $_POST['expiration_date'] ?? [];
    
            foreach ($productIds as $index => $productId) {
                $imagePath = $this->handleImageUpload($index);
                $categoryId = $categoryIds[$index];
                $category = $this->categories->getCategoryById($categoryId);
    
                if (!$category) {
                    die("Invalid category selected.");
                }
    
                $existingInventory = $this->model->getInventoryById($productId);
    
                if ($existingInventory) {
                    // Product exists, update it by summing the quantity
                    $newQuantity = $existingInventory['quantity'] + (int)$quantities[$index]; // Sum with existing
                    $data = [
                        'product_name' => $existingInventory['product_name'],
                        'category_id' => $categoryId,
                        'category_name' => $category['name'],
                        'quantity' => $newQuantity, // Updated quantity
                        'amount' => $prices[$index], // Use new price
                        'total_price' => $this->calculateTotalPrice($newQuantity, $prices[$index]),
                        'expiration_date' => $expirationDates[$index] ?? $existingInventory['expiration_date'],
                        'image' => $imagePath ?: $existingInventory['image'],
                    ];
    
                    $this->model->updateInventory($productId, $data);
                } else {
                    // New product, create it as is
                    $newProductName = $_POST['new_product_name'][$index] ?? 'Unknown Product';
                    $data = [
                        'product_name' => $newProductName,
                        'category_id' => $categoryId,
                        'category_name' => $category['name'],
                        'quantity' => $quantities[$index],
                        'amount' => $prices[$index],
                        'total_price' => $this->calculateTotalPrice($quantities[$index], $prices[$index]),
                        'expiration_date' => $expirationDates[$index] ?? null,
                        'image' => $imagePath,
                    ];
    
                    $this->model->createInventory($data);
                }
            }
    
            $this->redirect('/inventory');
        }
    }

    /**
     * Show form to edit an inventory item
     */
    public function edit($id)
    {
        $inventory = $this->model->getInventorys($id);
        $categories = $this->categories->getCategory();
        $this->views('inventory/edit', compact('inventory', 'categories'));
    }

    /**
     * Update an inventory item
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventory = $this->model->getInventorys($id);
            $imagePath = $this->handleImageUpload(null, $inventory['image']);
            
            $categoryId = $_POST['category_id'];
            $category = $this->categories->getCategoryById($categoryId);
            $categoryName = $category['name'];
    
            $data = [
                'category_id' => $categoryId,
                'category_name' => $categoryName,
                'product_name' => $_POST['product_name'],
                'quantity' => $_POST['quantity'],
                'amount' => $_POST['amount'],
                'total_price' => $this->calculateTotalPrice($_POST['quantity'], $_POST['amount']),
                'expiration_date' => $_POST['expiration_date'],
                'image' => $imagePath,
            ];
    
            $this->model->updateInventory($id, $data);
            $this->redirect('/inventory');
        }
    }

    /**
     * Delete an inventory item
     */
    public function destroy()
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $this->model->deleteItem($_GET['id']);
            $this->redirect('/inventory');
        } else {
            die("Invalid ID");
        }
    }

    /**
     * View a single inventory item
     */
    public function view()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            die("Invalid ID provided.");
        }

        $inventory = $this->model->viewInventory($_GET['id']);
        if (!$inventory) {
            die("Inventory item not found.");
        }

        $this->views('inventory/view', compact('inventory'));
    }

    /**
     * Get product details via AJAX
     */
    public function getProductDetails()
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $inventory = $this->model->getInventoryById($_GET['id']);
            header('Content-Type: application/json');
            echo json_encode($inventory ?: ['error' => 'Product not found']);
            exit();
        }
    }
    /**
     * Helper method to calculate total price
     */
    private function calculateTotalPrice($quantity, $amount)
    {
        return $quantity * $amount;
    }

    /**
     * Handle image uploads
     */
    private function handleImageUpload($index = null, $existingImage = null)
    {
        if (isset($_FILES['image'])) {
            // Handle single image (edit case)
            if ($index === null && $_FILES['image']['error'] === 0) {
                $targetDir = "uploads/";
                $imagePath = $targetDir . uniqid() . '-' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
                return $imagePath;
            }
            // Handle array of images (create case)
            elseif (is_numeric($index) && $_FILES['image']['error'][$index] == 0) {
                $targetDir = "uploads/";
                $imagePath = $targetDir . uniqid() . '-' . basename($_FILES['image']['name'][$index]);
                move_uploaded_file($_FILES['image']['tmp_name'][$index], $imagePath);
                return $imagePath;
            }
        }
        return $existingImage;
    }
}
