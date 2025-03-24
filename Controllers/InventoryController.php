<?php
require_once './Models/InventoryModel.php';

class InventoryController {

    private $model;

    public function __construct() {
        $this->model = new InventoryModel();
    }

    // Show all inventory items
    public function index() {
        $inventoryItems = $this->model->getAllInventory();
        require_once 'views/inventory/list.php';  // Display inventory list
    }

    // Show form for adding inventory item
    public function create() {
        require_once 'views/inventory/create.php';
    }

    // Store new inventory item (handling image upload)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle image upload
            $imagePath = $this->uploadImage($_FILES['image']);

            // Prepare data
            $data = [
                'product_name' => $_POST['product_name'],
                'image' => $imagePath,  // Save the image path to the database
                'quantity' => $_POST['quantity'],
                'amount' => $_POST['amount'],
                'expiration_date' => $_POST['expiration_date']
            ];
            $this->model->addInventory($data);
            header("Location: /inventory");  // Redirect after adding
        }
    }

    // Show form for editing inventory item
    public function edit() {
        if (isset($_GET['id'])) {
            $inventoryItem = $this->model->getInventoryById($_GET['id']);
            require_once './views/inventory/edit.php';  // Display edit form
        }
    }

    // Update inventory item (handling image upload)
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle image upload (if new image is uploaded)
            $imagePath = $this->uploadImage($_FILES['image'], $_POST['current_image']);

            // Prepare data
            $data = [
                'id' => $_POST['id'],
                'product_name' => $_POST['product_name'],
                'image' => $imagePath,  // Save the new or old image path to the database
                'quantity' => $_POST['quantity'],
                'amount' => $_POST['amount'],
                'expiration_date' => $_POST['expiration_date']
            ];
            $this->model->updateInventory($data);
            header("Location: /inventory");  // Redirect after updating
        }
    }

    // Delete inventory item
    public function destroy() {
        if (isset($_GET['id'])) {
            $this->model->deleteInventory($_GET['id']);
            header("Location: /inventory");  // Redirect after deleting
        }
    }

    // Function to handle image upload
    private function uploadImage($file, $currentImage = null) {
        // Check if file is uploaded
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Set the target directory to store images
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($file['name']);
            
            // Check if file is an image
            if (getimagesize($file['tmp_name']) !== false) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    // If there was a current image, delete it (if applicable)
                    if ($currentImage && file_exists($currentImage)) {
                        unlink($currentImage);  // Delete old image
                    }
                    return $uploadFile;  // Return the path of the uploaded image
                } else {
                    throw new Exception("Failed to upload the image.");
                }
            } else {
                throw new Exception("The file is not a valid image.");
            }
        } else {
            // If no new image is uploaded, return the current image path
            return $currentImage;
        }
    }

    public function view()
    {
        // Get the ID from the URL query parameter
        $id = $_GET['id'] ?? null;

        // If no ID is provided, redirect or handle the error
        if (!$id) {
            die('Item ID not provided');
        }

        // Fetch the item from the database
        $inventoryItem = $this->model->getItemById($id);

        // If the item doesn't exist, handle the error
        if (!$inventoryItem) {
            die('Item not found');
        }

        // Include the view to display the item
        require_once './views/inventory/view.php'; // Adjust path to the actual view
    }

}
?>
