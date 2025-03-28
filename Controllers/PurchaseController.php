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

    function index()
    {
        $purchase = $this->model->getPurchases();
        $this->views('purchase/list', ['purchase' => $purchase]);
    }

    function create()
    {
        // Fetch categories to display in the form
        $categories = $this->model->getCategories();  // Ensure this function exists to fetch categories
        $this->views('purchase/create', ['categories' => $categories]);
    }

    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate category_id
            if (!isset($_POST['category_id']) || empty($_POST['category_id']) || !is_numeric($_POST['category_id'])) {
                echo "Invalid category selected.";
                return; // Stop further processing
            }
            $category_id = $_POST['category_id']; // Store validated category_id

            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $imagePath = $uploadDir . $imageName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $imagePath = null;
                }
            }

            // Prepare data for purchase creation
            $data = [
                'product_name'  => htmlspecialchars($_POST['product_name']),
                'category_name'  => htmlspecialchars($_POST['category_name']),
                'category_id'  => intval($category_id),  // Ensure category_id is an integer
                'price'  => floatval($_POST['price']),
                'purchase_date'  => $_POST['purchase_date'],
                'image' => $imagePath,
            ];

            // Create the purchase
            $this->model->createPurchase($data);
            $this->redirect('/purchase');
        }
    }

    function edit($id)
    {
        $purchase = $this->model->getPurchase($id);
        $categories = $this->model->getCategories();  // Fetch categories for the form
        $this->views('purchase/edit', ['purchase' => $purchase, 'categories' => $categories]);
    }

    function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $purchase = $this->model->getPurchase($id);
            $imagePath = $purchase['image'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $newImagePath = $uploadDir . $imageName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $newImagePath)) {
                    if ($imagePath && file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    $imagePath = $newImagePath;
                }
            }

            // Validate category_id
            if (!isset($_POST['category_id']) || empty($_POST['category_id']) || !is_numeric($_POST['category_id'])) {
                echo "Invalid category selected.";
                return; // Stop further processing
            }
            $category_id = $_POST['category_id']; // Store validated category_id

            // Prepare data for purchase update
            $data = [
                'product_name'  => htmlspecialchars($_POST['product_name']),
                'category_name'  => htmlspecialchars($_POST['category_name']),
                'category_id'  => intval($category_id),
                'price'  => floatval($_POST['price']),
                'purchase_date'  => $_POST['purchase_date'],
                'image' => $imagePath,
            ];

            // Update the purchase
            $this->model->updatePurchase($id, $data);
            $this->redirect('/purchase');
        }
    }

    function destroy($id)
    {
        $purchase = $this->model->getPurchase($id);
        
        if ($purchase['image'] && file_exists($purchase['image'])) {
            unlink($purchase['image']);
        }

        $this->model->deletePurchase($id);
        $this->redirect('/purchase');
    }
}
