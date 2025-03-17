<?php
require 'Router.php';
require_once './Controllers/DashboardController.php';
require_once './Controllers/ProductController.php';
require_once './Controllers/PurchaseController.php';
require_once './Controllers/LoginController.php';

// Create a Router instance
$router = new Router();

// Dashboard routes
$router->get('/dashboard/list', [DashboardController::class, 'index']);
$routes = new Router();

// dashboard

$routes->get('/', [DashboardController::class, 'index']);

//inventory
$routes->get('/inventory', [InventoryController::class, 'index']);

//Notifications
$routes->get('/notifications', [NotificationController::class, 'index']);

// products
$routes->get('/products', [ProductController::class, 'index']);


// purchase order
$routes->get('/purchase', [PurchaseController::class, 'index']);
$routes->get('/purchase/create', [PurchaseController::class, 'create']);
$routes->post('/purchase/store', [PurchaseController::class, 'store']);
$routes->get('/purchase/edit', [PurchaseController::class, 'edit']);
$routes->put('/purchase/update', [PurchaseController::class, 'update']);
$routes->delete('/purchase/destroy', [PurchaseController::class, 'destroy']);

// dispatch
$routes->dispatch();


// Products routes
$router->get('/products', [ProductController::class, 'index']);

// Purchase routes
$router->get('/purchase', [PurchaseController::class, 'index']);
$router->get('/purchase/create', [PurchaseController::class, 'create']);
$router->post('/purchase/store', [PurchaseController::class, 'store']);
$router->get('/purchase/edit', [PurchaseController::class, 'edit']);

// Login routes
$router->get('/', [new LoginController(), 'showLogin']); // Show login form
$router->post('/login', [new LoginController(), 'processLogin']); // Process login
$router->get('/logout', [new LoginController(), 'logout']); // Handle logout

// Dispatch the routes
$router->dispatch();
?>
