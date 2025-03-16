<?php
require 'Router.php';
require_once './Controllers/DashboardController.php';
require_once './Controllers/ProductController.php';
require_once './Controllers/PurchaseController.php';


$routes = new Router();

// dashboard

$routes->get('/', [DashboardController::class, 'index']);
$routes->get('/inventory', [InventoryController::class, 'index']);

// products
$routes->get('/products', [ProductController::class, 'index']);



// purchase order
$routes->get('/purchase', [PurchaseController::class, 'index']);
$routes->get('/purchase/create', [PurchaseController::class, 'create']);


// dispatch
$routes->dispatch();


require_once __DIR__ . '/../Controllers/LoginController.php';
require_once __DIR__ . '/../Controllers/DashboardController.php';
require_once __DIR__ . '/Router.php';

$router = new Router();

// Define the routes
$router->get('/login', [new LoginController(), 'showLogin']);
$router->post('/login', [new LoginController(), 'processLogin']);
$router->get('/logout', [new LoginController(), 'logout']);
$router->get('/dashboard/list', [new DashboardController(), 'showDashboardList']);

$router->dispatch();
?>
