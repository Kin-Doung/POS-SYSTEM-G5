<?php
// Set session ini settings before starting session
ini_set('session.cookie_secure', 1); // Enforce HTTPS in production
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once './Routers/Router.php';
require_once './Controllers/BaseController.php';
require_once './Controllers/LoginController.php';
require_once './Controllers/DashboardController.php';
require_once './Controllers/ProductController.php';
require_once './Controllers/PurchaseController.php';
require_once './Controllers/CategoryController.php';
require_once './Controllers/InventoryController.php';
require_once './Controllers/NotificationController.php';
require_once './Controllers/SettingController.php';
require_once './Controllers/LogoutController.php';
require_once './Controllers/LanguageController.php';
require_once './Controllers/HistoryController.php';
require_once './Controllers/Profit_LossController.php';
require_once './Controllers/TrackingController.php';
require_once './Controllers/CalendarController.php';
require_once './Controllers/NavController.php';

$router = new Router();

// Public routes
$router->get('/', [LoginController::class, 'showLogin']);
$router->post('/login', [LoginController::class, 'processLogin']);

// Protected routes
$router->get('/dashboard', [DashboardController::class, 'index'], true);
$router->get('/profit_loss/get_data', [DashboardController::class, 'get_data'], true);

// Navbar
$router->get('/nav', [NavController::class, 'index'], true);

// Settings
$router->get('/settings', [SettingController::class, 'index'], true);
$router->get('/settings/create', [SettingController::class, 'create'], true);
$router->post('/settings/store', [SettingController::class, 'store'], true);
$router->get('/settings/edit', [SettingController::class, 'edit'], true);
$router->put('/settings/update', [SettingController::class, 'update'], true);
$router->delete('/settings/destroy', [SettingController::class, 'destroy'], true);

// Inventory
$router->get('/inventory', [InventoryController::class, 'index'], true);
$router->get('/inventory/getProductByBarcode', [InventoryController::class, 'getProductByBarcode'], true);
$router->get('/inventory/create', [InventoryController::class, 'create'], true);
$router->post('/inventory/store', [InventoryController::class, 'store'], true);
$router->get('/inventory/edit/(:num)', [InventoryController::class, 'edit'], true);
$router->post('/inventory/update', [InventoryController::class, 'update'], true); // New route for AJAX
$router->post('/inventory/update/(:num)', [InventoryController::class, 'update'], true); // Keep for compatibility
$router->post('/inventory/destroy', [InventoryController::class, 'destroy'], true);
$router->get('/inventory/view/(:num)', [InventoryController::class, 'view'], true);
$router->post('/inventory/bulkDestroy', [InventoryController::class, 'bulkDestroy'], true);
$router->get('/inventory/getProductDetails', [InventoryController::class, 'getProductDetails'], true);

// Notifications
$router->get('/notifications', [NotificationController::class, 'index'], true);

// Products
$router->post('/products/submitCart', [ProductController::class, 'submitCart'], true);
$router->post('/products/syncQuantity', [ProductController::class, 'syncQuantity'], true);
$router->get('/products', [ProductController::class, 'index'], true);
$router->post('/products/store', [ProductController::class, 'store'], true);
$router->post('/products/delete/{id}', [ProductController::class, 'destroy'], true);
$router->post('/products/updatePrice', [ProductController::class, 'updatePrice'], true);

// Category
$router->get('/category', [CategoryController::class, 'index'], true);
$router->get('/category/create', [CategoryController::class, 'create'], true);
$router->post('/category/store', [CategoryController::class, 'store'], true);
$router->get('/category/edit', [CategoryController::class, 'edit'], true);
$router->put('/category/update', [CategoryController::class, 'update'], true);
$router->get('/category/delete', [CategoryController::class, 'delete'], true);

// Purchase
$router->get('/purchase', [PurchaseController::class, 'index'], true);
$router->get('/purchase/create', [PurchaseController::class, 'create'], true);
$router->post('/purchase/store', [PurchaseController::class, 'store'], true);
$router->get('/purchase/edit/{id}', [PurchaseController::class, 'edit'], true);
$router->post('/purchase/update/{id}', [PurchaseController::class, 'update'], true);
$router->post('/purchase/destroy/{id}', [PurchaseController::class, 'destroy'], true);
$router->post('/purchase/bulk-destroy', [PurchaseController::class, 'bulkDestroy'], true);

// History
$router->get('/history', [HistoryController::class, 'index'], true);
$router->post('/history/store', [HistoryController::class, 'store'], true);
$router->delete('/history/destroy', [HistoryController::class, 'destroy'], true);
$router->post('/history/fetchFilteredHistories', [HistoryController::class, 'fetchFilteredHistories'], true);

// Profit Loss
$router->get('/profit_loss', [Profit_LossController::class, 'index'], true);
$router->post('/profit_loss/store', [Profit_LossController::class, 'store'], true);
$router->get('/profit_loss/delete', [Profit_LossController::class, 'delete'], true);
$router->post('/profit_loss/destroy_multiple', [Profit_LossController::class, 'destroy_multiple'], true);

// Calendar
$router->get('/calendar', [CalendarController::class, 'index'], true);

// Logout and Misc
$router->get('/logout', [LogoutController::class, 'index'], true);
$router->get('/language', [LanguageController::class, 'index'], true);
$router->get('/tracking', [TrackingController::class, 'index'], true);

$router->dispatch();
