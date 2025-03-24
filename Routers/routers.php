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
require_once './Controllers/LoginController.php';

$routes = new Router();
// login
$routes->get('/', [LoginController::class, 'showLogin']); // Corrected the function name
$routes->post('/login', [LoginController::class, 'processLogin']); // Handle login processing
$routes->get('/dashboard', [DashboardController::class, 'index']); // Redirect to dashboard after successful login

// setting
$routes->get('/settings', [SettingController::class, 'index']);
$routes->get('/settings/create', [SettingController::class, 'create']);
$routes->post('/settings/store', [SettingController::class, 'store']);
$routes->get('/settings/edit/(:num)', [SettingController::class, 'edit']);
$routes->put('/settings/update/(:num)', [SettingController::class, 'update']);
$routes->delete('/settings/destroy/(:num)', [SettingController::class, 'destroy']);


//inventory
$routes->get('/inventory', [InventoryController::class, 'index']); // Display all inventory

$routes->get('/inventory/create', [InventoryController::class, 'create']); // Show create page

$routes->post('/inventory/store', [InventoryController::class, 'store']); // Store new inventory item

// Edit route should accept an 'id' parameter
$routes->get('/inventory/edit', [InventoryController::class, 'edit']); // Show edit page for a specific inventory item

// Update route should also accept an 'id' parameter
$routes->put('/inventory/update', [InventoryController::class, 'update']); // Update a specific inventory item

// Delete route should accept an 'id' parameter
// Example route definition in routers.php
$routes->delete('/inventory/delete', [InventoryController::class, 'destroy']); 




// $routes->delete('/inventory/delete', [InventoryController::class, 'destroy']); // Delete a specific inventory item


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
$routes->post('/purchase/restock', [PurchaseController::class, 'restock']);




// logout
$routes->get('/logout', [LogoutController::class, 'index']);

// language
$routes->get('/language', [LanguageController::class, 'index']);


// dispatch
$routes->dispatch();
