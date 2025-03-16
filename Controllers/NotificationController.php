<?php
require_once 'Models/NotificationModel.php';
require_once 'BaseController.php';
class NotificationController extends BaseController{
 
    function index()
    {
        $this->views('notifications/list');
    }
}