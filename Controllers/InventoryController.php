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
                    $imagePath = null; // If upload fails, set to null
                }
            }

            // Get category_id and category_name from the form
            $category_id = $_POST['category_id']; // Get category_id from the form
            $category_name = $_POST['category_name']; // Get category_name from the form

            // Prepare data
            $data = [
                'product_name' => $_POST['product_name'],
                'category_id' => $category_id,  // Include the category ID in the data
                'category_name' => $category_name,  // Include the category name in the data
                'quantity' => $_POST['quantity'],
                'amount' => $_POST['amount'],
                'expiration_date' => $_POST['expiration_date'],
                'image' => $imagePath
            ];

            // Insert the new product into the database
            $this->model->createInventory($data);

            // Redirect after successful insertion
            $this->redirect('/inventory');
        }
    }



    // Show the form to edit an existing inventory item
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
    // Update an inventory item
    function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

            $data = [
                'category_id' => $_POST['category_id'],
                'product_name' => $_POST['product_name'],
                'quantity' => $_POST['quantity'],
                'amount' => $_POST['amount'],
                'expiration_date' => $_POST['expiration_date'],
                'image' => $imagePath  // Make sure to include the image path
            ];

            $this->model->updateInventory($id, $data);
            $this->redirect('/inventory');
        }
    }

    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? null;

            // Debugging: Check if ID is being received
            if (!$id) {
                echo "ID is missing or invalid!";
                return;
            }

            $this->model->deleteItem($id);
            $this->redirect('/inventory');
        }
    }
}
