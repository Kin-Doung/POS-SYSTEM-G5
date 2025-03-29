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
        $categories = $this->model->getCategories();
        $this->views('purchase/create', ['categories' => $categories]);
    }
    // Assuming you're inside the PurchaseController

    public function store()
    {
        // Check if POST data exists and is valid
        if (!isset($_POST['product_name'], $_POST['category_id'], $_POST['quantity'], $_POST['amount'], $_POST['typeOfproducts'])) {
            // Handle missing data
            echo "Missing data!";
            return;
        }

        // Get POST data
        $product_names = $_POST['product_name'];
        $category_ids = $_POST['category_id'];
        $quantities = $_POST['quantity'];
        $amounts = $_POST['amount'];
        $type_of_products = $_POST['typeOfproducts'];

        // Loop through the products and store them
        foreach ($product_names as $index => $product_name) {
            // Validate individual fields (optional)
            if (empty($product_name) || !is_numeric($category_ids[$index]) || !is_numeric($quantities[$index]) || !is_numeric($amounts[$index])) {
                echo "Invalid input at index $index!";
                return;
            }

            // Insert product into the 'purchase' table
            $this->model->insertProduct($product_name, $category_ids[$index], $quantities[$index], $amounts[$index], $type_of_products[$index]);
        }

        // Redirect or show a success message
        header('Location: /purchase');
        exit();
    }




    function edit($id)
    {
        $purchase = $this->model->getPurchase($id);
        if (!$purchase) {
            // Handle case where purchase is not found
            echo "Purchase not found.";
            return;
        }
        $categories = $this->model->getCategories();  // Fetch categories for the form
        $this->views('purchase/edit', ['purchase' => $purchase, 'categories' => $categories]);
    }

    function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $purchase = $this->model->getPurchase($id);
            if (!$purchase) {
                // Handle case where purchase is not found
                echo "Purchase not found.";
                return;
            }
    
            $imagePath = $purchase['image'];
    
            // Handle image upload if a new image is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif']; // Add more if needed
                $fileMimeType = mime_content_type($_FILES['image']['tmp_name']);
    
                if (!in_array($fileMimeType, $allowedMimeTypes)) {
                    echo "Invalid file type!";
                    return;
                }
    
                $uploadDir = __DIR__ . '/../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
    
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $newImagePath = $uploadDir . $imageName;
    
                if (move_uploaded_file($_FILES['image']['tmp_name'], $newImagePath)) {
                    // Delete the old image if new image is uploaded successfully
                    if ($imagePath && file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    $imagePath = $newImagePath;
                } else {
                    echo "Image upload failed.";
                    return;
                }
            }
    
            // Validate category_id
            if (!isset($_POST['category_id']) || empty($_POST['category_id']) || !is_numeric($_POST['category_id'])) {
                echo "Invalid category selected.";
                return; // Stop further processing
            }
    
            // Prepare data for purchase update
            $data = [
                'product_name'  => htmlspecialchars($_POST['product_name']),
                'category_name'  => htmlspecialchars($_POST['category_name']),
                'category_id'  => intval($_POST['category_id']),
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
        if (!$purchase) {
            // Handle case where purchase is not found
            echo "Purchase not found.";
            return;
        }

        if ($purchase['image'] && file_exists($purchase['image'])) {
            unlink($purchase['image']); // Delete image file if exists
        }

        $this->model->deletePurchase($id);
        $this->redirect('/purchase');
    }
}
