<?php
require 'Router.php';
require_once './Controllers/BaseController.php';
require_once './Controllers/DashboardController.php';
require_once './Controllers/ProductController.php';
require_once './Controllers/PurchaseController.php';
require_once './Controllers/CategoryController.php';
require_once './Controllers/InventoryController.php';
require_once './Controllers/NotificationController.php';
require_once './Controllers/SettingController.php';
require_once './Controllers/LogoutController.php';
require_once './Controllers/LanguageController.php';

$routes = new Router();

// setting
$routes->get('/settings', [SettingController::class, 'index']);
$routes->get('/settings/store', [SettingController::class, 'store']);
$routes->get('/settings/edit', [SettingController::class, 'edit']);
$routes->put('/settings/update', [SettingController::class, 'update']);

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

// categories
$routes->get('/category', [CategoryController::class, 'index']);
$routes->get('/category/create', [CategoryController::class, 'create']);
$routes->post('/category/store', [CategoryController::class, 'store']);
$routes->get('/category/edit', [CategoryController::class, 'edit']);
$routes->put('/category/update', [CategoryController::class, 'update']);
$routes->delete('/category/destroy', [CategoryController::class, 'destroy']);

// purchase order
$routes->get('/purchase', [PurchaseController::class, 'index']);
$routes->get('/purchase/create', [PurchaseController::class, 'create']);
$routes->post('/purchase/store', [PurchaseController::class, 'store']);
$routes->get('/purchase/edit', [PurchaseController::class, 'edit']);
$routes->put('/purchase/update', [PurchaseController::class, 'update']);
$routes->delete('/purchase/destroy', [PurchaseController::class, 'destroy']);

// logout
$routes->get('/logout', [LogoutController::class, 'index']);

// language
$routes->get('/language', [LanguageController::class, 'index']);


// dispatch
$routes->dispatch();
