<?php
require_once './Models/InventoryModel.php';
require_once './Models/CategoryModel.php';

class InventoryController extends BaseController
{
    private $model;
    private $categories;

    function __construct()
    {
        $this->model = new InventoryModel();
        $this->categories = new CategoryModel();
    }

    // Show the inventory list
    function index()
    {
        $inventory = $this->model->getInventory();
        $categories = $this->model->getCategory(); // Fetch categories

        // Pass inventory and categories to the view
        $this->views('inventory/list', [
            'inventory' => $inventory,
            'categories' => $categories // Pass categories to the view
        ]);
    }

    // Show the form to create a new inventory item
    function create()
    {
        // Fetch categories from the CategoryModel (instead of InventoryModel)
        $categories = $this->categories->getCategory();
        // Pass categories to the view
        $this->views('inventory/create', ['categories' => $categories]);
    }

    // Store a new inventory item
    // In your controller store method
    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $imagePath = $uploadDir . $imageName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $imagePath = null;
                }
            }

            // Get category_id from the form
            $category_id = $_POST['category_id'];
            $category_name = $_POST['category_name'];  // Optional if you're also saving the category name

            // Calculate total_price
            $total_price = $this->calculateTotalPrice($_POST['quantity'], $_POST['amount']);

            // Prepare data for creating the inventory item
            $data = [
                'product_name' => $_POST['product_name'],
                'category_id' => $category_id,
                'category_name' => $category_name,
                'quantity' => $_POST['quantity'],
                'amount' => $_POST['amount'],
                'total_price' => $total_price,
                'expiration_date' => $_POST['expiration_date'],
                'image' => $imagePath
            ];

            // Insert new inventory item into the database
            $this->model->createInventory($data);

            // Redirect to inventory list
            $this->redirect('/inventory');
        }
    }


    // Show the form to edit an existing inventory item
    function edit($id)
    {
        $inventory = $this->model->getInventorys($id);  // Fetch the inventory item by ID
        $categories = $this->categories->getCategory(); // Fetch all categories

        // Pass both inventory and categories to the view
        $this->views('inventory/edit', [
            'inventory' => $inventory,
            'categories' => $categories
        ]);
    }

    // Update an inventory item
    function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get current inventory data
            $inventory = $this->model->getInventorys($id);
            $imagePath = $inventory['image']; // Keep old image path if no new image uploaded

            // Handle new image upload
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "uploads/"; // Ensure this folder exists
                $imagePath = $targetDir . basename($_FILES['image']['name']);

                // Move uploaded file to target directory
                if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    // Successfully uploaded new image
                } else {
                    echo "Failed to upload image.";
                    return;
                }
            }

            // Calculate total_price if not set
            $total_price = isset($_POST['total_price']) ? $_POST['total_price'] : $this->calculateTotalPrice($_POST['quantity'], $_POST['amount']);

            $data = [
                'category_id' => $_POST['category_id'],
                'product_name' => $_POST['product_name'],
                'quantity' => $_POST['quantity'],
                'amount' => $_POST['amount'],
                'total_price' => $total_price,  // Ensure total_price is being passed
                'expiration_date' => $_POST['expiration_date'],
                'image' => $imagePath
            ];

            $this->model->updateInventory($id, $data); // Pass the $data array with total_price

            // Redirect to the inventory list page
            $this->redirect('/inventory');
        }
    }


    // Destroy an inventory item
    public function destroy()
    {
        // Get the ID from the query string
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = $_GET['id'];  // Get the ID from the URL

            // Call the deleteItem method from the model to delete the inventory item
            $this->model->deleteItem($id);

            // Redirect back to the inventory list page
            header('Location: /inventory');
            exit();
        } else {
            echo "Invalid ID";  // Show error if ID is not valid
        }
    }

    // View an inventory item
    function view()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            die("Invalid ID provided.");
        }

        $id = $_GET['id']; // Get the ID from the URL parameter
        $inventory = $this->model->viewInventory($id); // Fetch inventory details with category

        if (!$inventory) {
            die("Inventory item not found.");
        }

        // Pass data to the view page
        $this->views('inventory/view', ['inventory' => $inventory]);
    }

    // Helper method to calculate total price
    private function calculateTotalPrice($quantity, $amount)
    {
        return $quantity * $amount; // Calculate total price as quantity * amount
    }
}
