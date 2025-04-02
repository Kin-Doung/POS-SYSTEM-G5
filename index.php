<?php
require_once './Routers/routers.php';

session_start();
require_once 'ProductController.php';

$controllerName = $_GET['controller'] ?? 'ProductController';
$action = $_GET['action'] ?? 'index';

$controller = new $controllerName();
$controller->$action();

?>

