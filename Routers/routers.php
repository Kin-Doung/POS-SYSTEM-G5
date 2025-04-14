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
require_once './Controllers/HistoryController.php';
require_once './Controllers/Profit_LossController.php';



$routes = new Router();
// login
$routes->get('/', [LoginController::class, 'showLogin']);
$routes->post('/login', [LoginController::class, 'processLogin']);
$routes->get('/dashboard', [DashboardController::class, 'index']);
$routes->get('/profit_loss/get_data', [DashboardController::class, 'get_data']);

// setting
$routes->get('/settings', [SettingController::class, 'index']);
$routes->get('/settings/create', [SettingController::class, 'create']);
$routes->post('/settings/store', [SettingController::class, 'store']);
$routes->get('/settings/edit', [SettingController::class, 'edit']);
$routes->put('/settings/update', [SettingController::class, 'update']);
$routes->delete('/settings/destroy', [SettingController::class, 'destroy']);



// Inventory
$routes->get('/inventory', [InventoryController::class, 'index']);
$routes->get('/inventory/getProductByBarcode', [InventoryController::class, 'getProductByBarcode']);
$routes->get('/inventory/create', [InventoryController::class, 'create']);
$routes->post('/inventory/store', [InventoryController::class, 'store']);
$routes->get('/inventory/edit/(:num)', [InventoryController::class, 'edit']);
$routes->post('/inventory/update/(:num)', [InventoryController::class, 'update']);
$routes->post('/inventory/destroy', [InventoryController::class, 'destroy']);
$routes->get('/inventory/view/(:num)', [InventoryController::class, 'view']);
$routes->post('/inventory/bulkDestroy', [InventoryController::class, 'bulkDestroy']);


// Stock tracking
$routes->get('/tracking', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);

$routes->get('/inventory/getProductDetails', [InventoryController::class, 'getProductDetails']);



// $routes->delete('/inventory/delete', [InventoryController::class, 'destroy']); // Delete a specific inventory item


//Notifications
$routes->get('/notifications', [NotificationController::class, 'index']);
$routes->post('/products/submitCart', [ProductController::class, 'submitCart']); // Process cart submission
$routes->post('/products/syncQuantity', [ProductController::class, 'syncQuantity']); // Sync quantity

$routes->get('/products', [ProductController::class, 'index']); // Show all products
$routes->post('/products/store', [ProductController::class, 'store']); // Store a new product
$routes->get('/purchase/edit/{id}', [PurchaseController::class, 'edit']);
$routes->post('/purchase/update/{id}', [PurchaseController::class, 'update']);
$routes->post('/products/delete/{id}', [ProductController::class, 'destroy']); // Delete a specific product
$routes->post('/products/updatePrice', [ProductController::class, 'updatePrice']); // Update product price

// categories
$routes->get('/category', [CategoryController::class, 'index']);
$routes->get('/category/create', [CategoryController::class, 'create']);
$routes->post('/category/store', [CategoryController::class, 'store']);
$routes->get('/category/edit', [CategoryController::class, 'edit']);
$routes->put('/category/update', [CategoryController::class, 'update']);
$routes->get('/category/delete', [CategoryController::class, 'delete']);  // Change from POST to GET



// Purchase Order Routes
$routes->get('/purchase', [PurchaseController::class, 'index']);
$routes->get('/purchase/create', [PurchaseController::class, 'create']);
$routes->post('/purchase/store', [PurchaseController::class, 'store']);
$routes->get('/purchase/edit/{id}', [PurchaseController::class, 'edit']);
$routes->post('/purchase/update/{id}', [PurchaseController::class, 'update']);;
$routes->post('/purchase/destroy/{id}', [PurchaseController::class, 'destroy']);
$routes->post('/purchase/bulk-destroy', [PurchaseController::class, 'bulkDestroy']);



// Router of the history product
$routes->get('/history', [HistoryController::class, 'index']);              // List all history entries
$routes->post('/history/store', [HistoryController::class, 'store']);       // Store a new history entry
$routes->delete('/history/destroy', [HistoryController::class, 'destroy']); // Delete a specific history entry (renamed from 'delete' to 'destroy')
$routes->post('/history/fetchFilteredHistories', [HistoryController::class, 'fetchFilteredHistories']); // Fetch filtered history entries

// Profit_Loss
$routes->get('/profit_loss', [Profit_LossController::class, 'index']);
$routes->post('/profit_loss/store', [Profit_LossController::class, 'store']);
$routes->get('/profit_loss/delete', [Profit_LossController::class, 'delete']); // Note: This seems unused
$routes->post('/profit_loss/destroy_multiple', [Profit_LossController::class, 'destroy_multiple']); // Add this


// logout
$routes->get('/logout', [LogoutController::class, 'index']);

// language
$routes->get('/language', [LanguageController::class, 'index']);


// dispatch
$routes->dispatch();
