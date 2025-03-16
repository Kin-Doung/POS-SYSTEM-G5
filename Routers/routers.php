<?php
require 'Router.php';
require_once './Controllers/DashboardController.php';
require_once './Controllers/ProductController.php';
require_once './Controllers/PurchaseController.php';

$routes = new Router();

// setting
$routes->get('/settings', [SettingController::class, 'index']);

$routes->get('/', [DashboardController::class, 'index']);

//inventory
$routes->get('/inventory', [InventoryController::class, 'index']);

//Notifications
$routes->get('/notifications', [NotificationController::class, 'index']);

// products
$routes->get('/products', [ProductController::class, 'index']);
$routes->post('/products/store', [ProductController::class, 'store']);
$routes->get('/products/edit/(:num)', [ProductController::class, 'edit']);  // Edit product with ID
$routes->post('/products/updatePrice/(:num)', [ProductController::class, 'updatePrice']); // Update price for a specific product


// purchase order
$routes->get('/purchase', [PurchaseController::class, 'index']);
$routes->get('/purchase/create', [PurchaseController::class, 'create']);
$routes->post('/purchase/store', [PurchaseController::class, 'store']);
$routes->get('/purchase/edit', [PurchaseController::class, 'edit']);
$routes->put('/purchase/update', [PurchaseController::class, 'update']);
$routes->delete('/purchase/destroy', [PurchaseController::class, 'destroy']);

// dispatch
$routes->dispatch();
