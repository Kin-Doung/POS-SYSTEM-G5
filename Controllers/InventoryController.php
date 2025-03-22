<?php
require_once './Models/InventoryModel.php';

class InventoryController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new InventoryModel();
    }

    public function index()
    {
        $inventory = $this->model->getInventory();
        $this->views('inventory/list', ['inventory' => $inventory]);
    }

    function create()
    {
        $this->views('inventory/create');
    }

    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $productName = $_POST['product_name'] ?? null;
                $productPrice = $_POST['price'] ?? null;
                $quantity = $_POST['quantity'] ?? null;
                $expirationDate = $_POST['expiration_date'] ?? null;
                $imageName = null;

                if (!$productName || !$productPrice || !$quantity || !$expirationDate) {
                    die("Missing required fields!");
                }

                // Handle Image Upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    $imageName = time() . "_" . $_FILES['image']['name'];
                    $targetDir = "./uploads/";

                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }

                    $targetFile = $targetDir . $imageName;
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        die("Image upload failed!");
                    }
                } else {
                    die("Image file is required!");
                }

                // Calculate total amount
                $totalAmount = $productPrice * $quantity;

                // Prepare data
                $data = [
                    'product_name' => $productName,
                    'image' => $imageName,
                    'quantity' => $quantity,

                    'amount' => $totalAmount,
                    'expiration_date' => $expirationDate,
                ];

                // Save data to the database
                $this->model->addInventory($data);
                echo "Product added successfully!";

                // Redirect to inventory
                header("Location: /inventory");
                exit;
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        }
    }



    public function edit($id)
    {
        $inventory = $this->model->getInventoryById($id);
        if (!$inventory) {
            $this->redirect('/inventory');
        }
        $this->views('inventory/edit', ['inventory' => $inventory]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inventory = $this->model->getInventoryById($id);
            if (!$inventory) {
                $this->redirect('/inventory');
            }

            $imagePath = $inventory['image'];

            // Check if the user has uploaded a new image
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $imagePath = time() . "_" . $_FILES['image']['name'];
                $targetDir = "uploads/";

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $targetFile = $targetDir . $imagePath;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            }

            // Prepare data to update inventory
            $data = [
                'product_name' => $_POST['product_name'],
                'image' => $imagePath,
                'quantity' => $_POST['quantity'],
            ];

            $this->model->updateInventory($id, $data);
            $this->redirect('/inventory');
        }
    }

    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? null;

            if ($id) {
                $this->model->deleteInventory($id);
            }
        }
        $this->redirect('/inventory');
    }
}
