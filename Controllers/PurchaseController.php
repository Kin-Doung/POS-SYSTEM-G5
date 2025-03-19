<?php
require_once './Models/PurchaseModel.php';
require_once './Models/ProductModel.php'; // Include the ProductModel

class PurchaseController extends BaseController
{
    private $model;
    private $productModel; // Add reference to ProductModel

    function __construct()
    {
        $this->model = new PurchaseModel();
        $this->productModel = new ProductModel(); // Instantiate the ProductModel
    }
    public function index()
    {
        $purchases = $this->model->getPurchase();
        $categories = $this->model->getCategory(); // ✅ Fetch categories
        $this->views('purchase/list', ['purchases' => $purchases, 'categories' => $categories]);
    }

    public function create()
    {
        $purchaseModel = new PurchaseModel();
        $categories = $purchaseModel->getCategory();
        $this->views('purchase/create', ['categories' => $categories]);
    }
    

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Retrieve form data
            $productName = $_POST['product_name'];
            $productPrice = $_POST['price'];
            $quantity = 0; // Default quantity
            $categoryId = $_POST['category_id']; // Get the category ID from the form
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

            // Insert the product into the 'products' table
            $productData = [
                'product_name' => $_POST['product_name'],
                'name' => $productName,
                'category_id' => $categoryId,
                'price' => $productPrice,
                'image' => $imageName,
                'quantity' => $quantity,
               
            ];

            // Insert product data and get the product ID
            $productId = $this->productModel->createProduct($productData); // Use ProductModel to insert product

            // Insert purchase data
            $purchaseData = [
                'product_name' => $_POST['product_name'],
                'product_id' => $productId, // Product ID from the product table
                'category_id' => $categoryId,
                'price' => $productPrice,
                'quantity' => $quantity,
                'purchase_date' => $purchaseDate,
                'image' => $imageName
            ];

            // Save purchase data
            $this->model->createPurchase($purchaseData); // Call the model to insert the data
            $this->redirect('/purchase');
        }
    }

    public function edit($id)
    {
        $purchaseModel = new PurchaseModel();  // Fetch categories from the database
        $purchase = $purchaseModel->getPurchases($id);
        $this->views('purchase/edit', ['purchase' => $purchase]);
    }

    // Update Purchase Controller's update method
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get current purchase data
            $purchase = $this->model->getPurchases($id);
            if (!$purchase) {
                $this->redirect('/purchase'); // Redirect if not found
            }
    
            $imagePath = $purchase['image']; // Keep existing image by default
    
            // Check if user uploaded a new image
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $imagePath = time() . "_" . $_FILES['image']['name']; // Unique file name
                $targetDir = "./uploads/";
    
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
    
                $targetFile = $targetDir . $imagePath;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            } else {
                // Keep existing image if no new one uploaded
                $imagePath = $_POST['existing_image'];
            }
    
            // ✅ Ensure `category_id` is included
            $data = [
                'product_name' => $_POST['product_name'],
                'image' => $imagePath,
                'price' => $_POST['price'],
                'category_id' => $_POST['category_id'], // Fix: Ensure category is updated
            ];
    
            // Update the purchase
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
