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

// staff login
$routes->get('/view', [UserController::class, 'index']);
$routes->get('/view/create', [UserController::class, 'create']);
$routes->post('/view/store', [UserController::class, 'store']);
$routes->get('/view/edit', [UserController::class, 'edit']);
$routes->put('/view/update', [UserController::class, 'update']);
$routes->delete('/view/delete', [UserController::class, 'destroy']);

// setting
$routes->get('/settings', [SettingController::class, 'index']);
$routes->get('/settings/create', [SettingController::class, 'create']);
$routes->post('/settings/store', [SettingController::class, 'store']);
$routes->get('/settings/edit', [SettingController::class, 'edit']);
$routes->put('/settings/update/(:num)', [SettingController::class, 'update']);
$routes->delete('/settings/destroy', [SettingController::class, 'destroy']);


//inventory
$routes->get('/inventory', [InventoryController::class, 'index']); // Display all inventory
$routes->get('/inventory/create', [InventoryController::class, 'create']); // Show create page
$routes->post('/inventory/store', [InventoryController::class, 'store']); // Store new inventory item
$routes->get('/inventory/edit', [InventoryController::class, 'edit']); // Show edit page for a specific inventory item
$routes->put('/inventory/update', [InventoryController::class, 'update']); // Update a specific inventory item
$routes->get('/inventory/delete', [InventoryController::class, 'destroy']);
$routes->get('/inventory/view', [InventoryController::class, 'view']); // View an inventory item




//Notifications
$routes->get('/notifications', [NotificationController::class, 'index']);

// products
$routes->get('/products', [ProductController::class, 'index']);
$routes->post('/products/store', [ProductController::class, 'store']);
$routes->get('/products/edit', [ProductController::class, 'edit']);  // Edit product with ID
$routes->post('/products/updatePrice', [ProductController::class, 'updatePrice']); // Update price for a specific product
$routes->post('/products/update-quantity', [ProductController::class, 'updateProductQuantity']);


// categories
$routes->get('/category', [CategoryController::class, 'index']);
$routes->get('/category/create', [CategoryController::class, 'create']);
$routes->post('/category/store', [CategoryController::class, 'store']);
$routes->get('/category/edit', [CategoryController::class, 'edit']);
$routes->put('/category/update', [CategoryController::class, 'update']);
$routes->get('/category/delete', [CategoryController::class, 'delete']);  // Change from POST to GET



// purchase order
$routes->get('/purchase', [PurchaseController::class, 'index']);
$routes->get('/purchase/create', [PurchaseController::class, 'create']);
$routes->post('/purchase/store', [PurchaseController::class, 'store']);
$routes->get('/purchase/edit/(:num)', [PurchaseController::class, 'edit']);
$routes->post('/purchase/update/(:num)', [PurchaseController::class, 'update']);
$routes->post('/purchase/destroy/(:num)', [PurchaseController::class, 'destroy']);
$routes->post('/purchase/bulk-destroy', [PurchaseController::class, 'bulkDestroy']);
$routes->get('/purchase/get-existing-products', [PurchaseController::class, 'getExistingProducts']);



// logout
$routes->get('/logout', [LogoutController::class, 'index']);

// language
$routes->get('/language', [LanguageController::class, 'index']);


// dispatch
$routes->dispatch();
