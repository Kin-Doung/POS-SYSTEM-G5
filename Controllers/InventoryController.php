<?php
require_once 'Models/InventoryModel.php';


class InventoryController extends BaseController
{
    function index()
    {
        // $users = $this->model->getUsers();
        $this->views('inventory/list');
    }
}