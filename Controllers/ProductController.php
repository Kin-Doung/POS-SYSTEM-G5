<?php
require_once 'Models/ProductModel.php';
require_once 'BaseController.php';
class ProductController extends BaseController
{
    function index()
    {
        $this->views('products/list');
    }
}