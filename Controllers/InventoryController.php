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
    // Store a new inventory item
    // Store a new inventory item
    // Store a new inventory item
// Store multiple inventory items
function store()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle multiple product entries
        $imagePaths = []; // Array to hold the paths for all images
        $categoryIds = $_POST['category_id']; // Assuming this is an array for multiple products
        $productNames = $_POST['product_name'];
        $quantities = $_POST['quantity'];
        $prices = $_POST['amount'];
        $expirationDates = $_POST['expiration_date'];

        // Loop through all the products in the form
        foreach ($productNames as $index => $productName) {
            // Handle image upload for each product
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'][$index] == 0) {
                $targetDir = "uploads/"; // Directory for storing images
                $imageName = uniqid() . '-' . basename($_FILES['image']['name'][$index]); // Unique image name to avoid collisions
                $imagePath = $targetDir . $imageName;

                // Check if the uploaded file is a valid image type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($_FILES['image']['type'][$index], $allowedTypes) && $_FILES['image']['size'][$index] <= 2 * 1024 * 1024) { // 2MB max
                    // Move the uploaded image to the uploads directory
                    if (move_uploaded_file($_FILES['image']['tmp_name'][$index], $imagePath)) {
                        // Image uploaded successfully
                    } else {
                        echo "Error uploading image.";
                        return; // Stop the process if image upload fails
                    }
                } else {
                    echo "Invalid file type or file is too large.";
                    return; // Stop the process if validation fails
                }
            }

            // Verify that category_id exists in the database
            $categoryId = $categoryIds[$index]; // Get category for this product
            $category = $this->categories->getCategoryById($categoryId); // Check if category exists
            if (!$category) {
                echo "Invalid category selected.";  // Show error if category doesn't exist
                return;
            }

            // Proceed with storing the inventory item
            $data = [
                'product_name' => $productNames[$index],
                'category_id' => $categoryId, // Store the selected category_id
                'category_name' => $category['name'], // Store the category name for reference
                'quantity' => $quantities[$index],
                'amount' => $prices[$index],
                'total_price' => $quantities[$index] * $prices[$index],
                'expiration_date' => $expirationDates[$index],
                'image' => $imagePath ?? null, // Store the image path if available
            ];

            // Store the inventory item in the database
            $this->model->createInventory($data);
        }

        // Redirect to inventory list after storing
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




    function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get current inventory data
            $inventory = $this->model->getInventorys($id);
            $imagePath = $inventory['image']; // Keep old image path if no new image uploaded

            // Handle new image upload
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "uploads/"; // Ensure this folder exists
                $imagePath = $targetDir . uniqid() . '-' . basename($_FILES['image']['name']); // Unique name for the image

                // Validate the image type and size
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] <= 2 * 1024 * 1024) {
                    // Move uploaded file to target directory
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                        // Successfully uploaded new image
                    } else {
                        echo "Failed to upload image.";
                        return;
                    }
                } else {
                    echo "Invalid file type or file is too large.";
                    return; // Stop the process if validation fails
                }
            }

            // Get the category_name from category_id
            $categoryId = $_POST['category_id'];
            $category = $this->categories->getCategoryById($categoryId); // Fetch category by ID
            $categoryName = $category['name']; // Get category name

            // Prepare the data array to update the inventory item
            $data = [
                'category_id' => $categoryId, // Update category_id
                'category_name' => $categoryName, // Update category_name
                'product_name' => $_POST['product_name'],
                'quantity' => $_POST['quantity'],
                'amount' => $_POST['amount'],
                'total_price' => $_POST['quantity'] * $_POST['amount'],
                'expiration_date' => $_POST['expiration_date'],
                'image' => $imagePath // Save new or old image path
            ];

            // Update the inventory item in the database
            $this->model->updateInventory($id, $data);

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
