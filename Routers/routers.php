<?php
require 'Router.php';
require_once './Controllers/DashboardController.php';
require_once './Controllers/ProductController.php';

$routes = new Router();

// dashboard

$routes->get('/', [DashboardController::class, 'index']);

// products
$routes->get('/products', [ProductController::class, 'index']);



// dispatch
$routes->dispatch();





