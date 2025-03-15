<?php
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
