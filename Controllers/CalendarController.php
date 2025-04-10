<?php
require_once __DIR__ . '/BaseController.php';
require_once './Models/CalendarModel.php';


class CalendarController extends BaseController
{
    private $model;
    function __construct()
    {
        $this->model =  new CategoryModel();
    }
    function index()
    {
        $category = $this->model->getCategory();
        $this->views('calendar/list', ['categories' => $category]);
    }
}
