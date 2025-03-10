<?php
require './Routers/Router.php';
require_once './Controllers/DashboardController.php';
require_once './Controllers/LoginController.php';


$routes = new Router();

// dashboard

$routes->get('/', [DashboardController::class, 'index']);

// dispatch
$routes->dispatch();
// Create a Router instance
$router = new Router();

// Initialize the database connection
$database = new Database();
$dbConnection = $database->getConnection();

// Define routes and pass the database connection to controllers
$router->add('/', function() use ($dbConnection) {
    $controller = new LoginController($dbConnection);
    $controller->showLogin();
});

$router->add('/login', function() use ($dbConnection) {
    $controller = new LoginController($dbConnection);
    $controller->loginAction();
});

$router->add('/home', function() use ($dbConnection) {
    $controller = new DashboardController($dbConnection);
    $controller->index();
});

$router->add('/logout', function() use ($dbConnection) {
    $controller = new LoginController($dbConnection);
    $controller->logoutAction();
});


// Handle the incoming URL
$requestedUrl = $_SERVER['REQUEST_URI'];
$controllerAction = $router->match($requestedUrl);

// Parse the controller and action from the route
list($controllerName, $actionName) = explode('@', $controllerAction);

// Create controller instance and call the method
$controller = new $controllerName($dbConnection);
$controller->$actionName();

