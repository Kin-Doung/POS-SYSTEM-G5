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
        $categories = $this->categories->getCategory(); // Fetch categories

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

            // Get category_id from the form
            $category_id = $_POST['category_id'];

            // Prepare data
            $data = [
                'product_name' => $_POST['product_name'],
                'category_id' => $category_id,  // Include the category ID in the data
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

    // Show the form to edit an inventory item
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
            $inventory = $this->model->getInventorys($id);
            $imagePath = $inventory['image']; // Keep old image if no new one is uploaded

            // Calculate the total price based on updated quantity and amount
            $totalPrice = $_POST['quantity'] * $_POST['amount'];

            // Handle new image upload if any
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "uploads/";
                $imagePath = $targetDir . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
            }

            // Prepare data for updating
            $data = [
                'category_id' => $_POST['category_id'],
                'product_name' => $_POST['product_name'],
                'quantity' => $_POST['quantity'],
                'amount' => $_POST['amount'],
                'expiration_date' => $_POST['expiration_date'],
                'total_price' => $totalPrice,  // Ensure total price is updated
                'image' => $imagePath
            ];

            // Call the model to update the inventory item
            $this->model->updateInventory($id, $data);

            // Redirect back to the inventory list page
            $this->redirect('/inventory');
        }
    }

    // Update the quantities and total_price in the database
    public function updateQuantity()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get the items array from the POST data
            $items = $_POST['items'];

            // Loop through each item and update the database
            foreach ($items as $item) {
                $id = $item['id'];
                $quantity = $item['quantity'];
                $totalPrice = $item['total_price'];

                // Prepare data to update
                $data = [
                    'quantity' => $quantity,
                    'total_price' => $totalPrice
                ];

                // Call model to update inventory item
                $this->model->updateInventory($id, $data);
            }

            // Return a success response
            echo json_encode(['status' => 'success']);
        }
    }

    // Update the price of an inventory item
    public function updatePrice()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get data from the POST request
            $id = $_POST['id'];
            $quantity = $_POST['quantity'];
            $totalPrice = $_POST['total_price'];

            // Prepare data for updating
            $data = [
                'quantity' => $quantity,
                'total_price' => $totalPrice
            ];

            // Update the inventory item in the database
            $this->model->updateInventory($id, $data);

            // Return a success response
            echo json_encode(['status' => 'success']);
        }
    }

    // Delete an inventory item
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
