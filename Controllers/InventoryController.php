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
                $productName = $_POST['product_name'];
                $productPrice = $_POST['price'];
                $quantity = $_POST['quantity']; // User-defined quantity
                $imageName = null;
                $addedDate = date('Y-m-d H:i:s');

                // Handle Image Upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    $imageName = time() . "_" . $_FILES['image']['name'];
                    $targetDir = "./uploads/";

                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }

                    $targetFile = $targetDir . $imageName;
                    move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
                }

                // Save data to database
                $data = [
                    'product_name' => $productName,
                    'image' => $imageName,
                    'quantity' => $quantity,
                    'price' => $productPrice,
                    'added_date' => $addedDate,
                ];

                $this->model->addInventory($data);
                $this->redirect('/inventory');
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
                    'price' => $_POST['price'],
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
