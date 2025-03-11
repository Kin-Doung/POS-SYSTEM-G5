<?php
require 'Router.php';
require 'Controllers/UserController.php';
require_once './Controllers/DashboardController.php';
require_once './Controllers/PurchaseController.php';


$routes = new Router();

// dashboard

$routes->get('/', [DashboardController::class, 'index']);

// user 

$routes->get('/user', [UserController::class, 'index']);


// purchase order
$routes->get('/purchase', [PurchaseController::class, 'index']);


// dispatch
$routes->dispatch();





// require './app/controllers/UserController.php';
// $uri = parse_url($_SERVER['REQUEST_URI'])["path"];
// $id = isset($_GET['id']) ? $_GET['id'] : null;
// $controller = new UserController();
// if ($uri == '/') {
//     $controller->index();
// } elseif ($uri == '/user/create') {
//     $controller->create();
// } elseif ($uri == '/user/store') {
//     $controller->store();
// } elseif ($uri == '/user/edit') {
//     $controller->edit($id);
// } elseif ($uri == '/user/update') {
//     $controller->update($id);
// } elseif ($uri == '/user/delete') {
//     $controller->destroy($id);
// }
