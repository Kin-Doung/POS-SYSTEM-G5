<?php
require 'Router.php';
require 'Controllers/UserController.php';
require_once './Controllers/DashboardController.php';

$routes = new Router();

// dashboard

$routes->get('/', [DashboardController::class, 'index']);

// user 

$routes->get('/user', [UserController::class, 'index']);
$routes->get('/user/create', [UserController::class, 'create']);
$routes->post('/user/store', [UserController::class, 'store']);
$routes->get('/user/edit', [UserController::class, 'edit']);
$routes->put('/user/update', [UserController::class, 'update']);
$routes->delete('/user/delete', [UserController::class, 'destroy']);

// department


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
