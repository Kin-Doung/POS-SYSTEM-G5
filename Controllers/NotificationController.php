<?php
require_once 'Models/NotificationModel.php';

class NotificationController extends BaseController{
 
    function index()
    {
        $this->views('notifications/list');
    }
}