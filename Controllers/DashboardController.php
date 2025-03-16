<?php
require_once 'Models/DashboardModel.php';

class DashboardController extends BaseController
{
    function index()
    {
        $this->views('dashboards/list');
    }
}