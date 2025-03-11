<?php
require 'Router.php';
require_once './Controllers/DashboardController.php';
require_once './Controllers/ProductController.php';
require_once './Controllers/PurchaseController.php';


$routes = new Router();

// dashboard

$routes->get('/', [DashboardController::class, 'index']);

// products
$routes->get('/products', [ProductController::class, 'index']);

$routes->get('/user', [UserController::class, 'index']);


// purchase order
$routes->get('/purchase', [PurchaseController::class, 'index']);


// dispatch
$routes->dispatch();





