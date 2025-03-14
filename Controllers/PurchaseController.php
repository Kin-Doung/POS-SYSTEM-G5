<?php
require_once 'Models/PurchaseModel.php';
require_once 'BaseController.php';

class PurchaseController extends BaseController {
    private $model;
    function __construct()
    {
     
        $this->model = new PurchaseModel();
    }

    public function index() {
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
            $data = [
                'product_name' => $_POST['product_name'],
                'image' => $_POST['image'],
                'quantity' => $_POST['quantity'],
                'price' => $_POST['price'],
                'purchase_date' => $_POST['purchase_date'],
                'id' => $_POST['id'],
            ];
            $this->model->getPurchases($data);
            $this->redirect('/purchase');
        }
    }
}
