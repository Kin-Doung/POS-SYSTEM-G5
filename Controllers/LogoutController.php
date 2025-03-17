<?php
require_once './Models/LogoutModel.php';

class LogoutController extends BaseController{
 
    function index()
    {
        $this->views('logout/list');
    }
}