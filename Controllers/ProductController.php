<?php
require_once 'Models/ProductModel.php';
require_once './Models/ProductModel.php';

class ProductController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new ProductModel();
    }

    public function index()
    {
        $purchases = $this->model->getPurchasesWithProductDetails();
        $this->Views('products/list', ['purchases' => $purchases]);
    }

    // ProductController.php

    public function edit($id)
    {
        $product = $this->model->getProduct($id);
        $this->Views('products/edit', ['product' => $product]);
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

