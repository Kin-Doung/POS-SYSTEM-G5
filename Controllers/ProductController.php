<?php
require_once 'Models/ProductModel.php';

class ProductController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->views('products/list');
    }

    public function index()
    {
        $purchases = $this->model->getPurchasesWithProductDetails();
        $purchases = $this->model->getPurchase();
        $categories = $this->model->getCategory(); // ✅ Fetch categories
        $this->views('products/list', ['purchases' => $purchases, 'purchases' => $purchases, 'categories' => $categories]);
    }

    // ProductController.php

    public function edit($id)
    {
        $product = $this->model->getProduct($id);
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
