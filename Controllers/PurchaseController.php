<?php
require_once 'Models/PurchaseModel.php';
require_once 'BaseController.php';

class PurchaseController extends BaseController {

    public function index() {
        $this->views('purchase/list.php');
    }
}
