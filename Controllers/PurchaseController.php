<?php
require_once 'Models/PurchaseModel.php';
require_once 'BaseController.php';

class PurchaseController extends BaseController
{
    private $model;
    function __construct()
    {

        $this->model = new PurchaseModel();
    }

    public function index()
    {
        $purchase = $this->model->getPurchase();
        $this->Views('purchase/list', ['purchases' => $purchase]);
    }
    function create()
    {
        $this->Views('purchase/create');
    }
    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productName = $_POST['product_name'];
            $productPrice = $_POST['price'];
            $quantity = 1; // Default quantity
            $imageName = null;
            $purchaseDate = date('Y-m-d H:i:s'); // Current date and time

            // Handle Image Upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $imageName = time() . "_" . $_FILES['image']['name']; // Unique file name
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
                'purchase_date' => $purchaseDate,
            ];

            $this->model->createPurchase($data);
            $this->redirect('/purchase');
        }
    }
    public function edit($id)
    {
        $purchase = $this->model->getPurchases($id);
        if (!$purchase) {
            // Handle error if the purchase doesn't exist
            $this->redirect('/purchase');
        }
        $this->Views('purchase/edit', ['purchase' => $purchase]);
    }


    // Update Purchase Controller's update method
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get current purchase data
            $purchase = $this->model->getPurchases($id);
            if (!$purchase) {
                // Handle error if the purchase doesn't exist
                $this->redirect('/purchase');
            }

            $imagePath = $purchase['image']; // Store the current image path

            // Check if the user has uploaded a new image
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                // Handle Image Upload
                $imagePath = time() . "_" . $_FILES['image']['name']; // Unique file name
                $targetDir = "./uploads/";

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $targetFile = $targetDir . $imagePath;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            }

            // Prepare data to update purchase
            $data = [
                'product_name'  => $_POST['product_name'],
                'image' => $imagePath,
                'price'  => $_POST['price'],
            ];

            // Update purchase data in the database
            $this->model->updatePurchase($id, $data);
            $this->redirect('/purchase');
        }
    }

    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? null; // Get the ID from POST request

            if ($id) {
                $this->model->deletePurchase($id);
            }
        }
        $this->redirect('/purchase'); // Redirect after deletion
    }
    
}
