<?php
require_once 'Models/DashboardModel.php';
require_once 'BaseController.php';
class DashboardController extends BaseController
{
    function index()
    {
        $this->views('Views/dashboards/list.php');
    }
}
