<?php
require_once 'Models/ProductModel.php';

class ProductController extends BaseController
{
    private $model;

    function __construct()
    {
        // Initialize the model here
        $this->model = new ProductModel();
    }

    public function index()
    {
        // Now, you can call the method on the model
        $purchases = $this->model->getPurchasesWithProductDetails();
        $categories = $this->model->getCategory();
    
        // Pass data to the views
        $this->views('products/list', ['purchase' => $purchases, 'categories' => $categories]);
    }

    public function edit($id)
    {
        // Fetch product details based on ID
        $product = $this->model->getProducts($id);
        $this->views('products/edit', ['product' => $product]);
    }

    public function updatePrice($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPrice = $_POST['price'] ?? null;

            if ($newPrice !== null) {
                $this->model->updatePrice($id, $newPrice);
                $this->redirect('/products'); // Redirect after updating the price
            } else {
                echo "Price is required.";
            }
        } else {
            echo "Invalid request method.";
        }
    }
}
