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
require_once __DIR__ . '/../Controllers/CalendarController.php';



$routes = new Router();
// login
$routes->get('/', [LoginController::class, 'showLogin']); // Corrected the function name
$routes->post('/login', [LoginController::class, 'processLogin']); // Handle login processing
$routes->get('/dashboard', [DashboardController::class, 'index']); // Redirect to dashboard after successful login
$routes->get('/profit_loss/get_data', [DashboardController::class, 'get_data']);

// setting
$routes->get('/settings', [SettingController::class, 'index']);
$routes->get('/settings/create', [SettingController::class, 'create']);
$routes->post('/settings/store', [SettingController::class, 'store']);
$routes->get('/settings/edit', [SettingController::class, 'edit']);
$routes->put('/settings/update', [SettingController::class, 'update']);
$routes->delete('/settings/destroy', [SettingController::class, 'destroy']);



//inventory
$routes->get('/inventory', [InventoryController::class, 'index']); // Display all inventory

$routes->get('/inventory/create', [InventoryController::class, 'create']); // Show create page

$routes->post('/inventory/store', [InventoryController::class, 'store']); // Store new inventory item

// Edit route should accept an 'id' parameter
$routes->get('/inventory/edit', [InventoryController::class, 'edit']); // Show edit page for a specific inventory item

// Update route should also accept an 'id' parameter
$routes->put('/inventory/update', [InventoryController::class, 'update']); // Update a specific inventory item

// Delete route should accept an 'id' parameter
// Assuming you're using some kind of routing library
$routes->get('/inventory/delete', [InventoryController::class, 'destroy']);

$routes->get('/inventory/view', [InventoryController::class, 'view']); // View an inventory item


// Stock tracking
$routes->get('/tracking', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);
// $routes->get('/stock', [TrackingController::class, 'index']);

// Calendar
$routes->get('/calendar', [CalendarController::class, 'index']);



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
