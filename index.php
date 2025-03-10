<?php
// login require --------------------------------------------------------------------
require_once 'Routers/Router.php';
require_once 'Controllers/LoginController.php';
require_once 'Controllers/DashboardController.php';
require_once 'Models/UserModel.php';
require_once 'Databases/database.php';

// Create Router instance
$router = new Router();

// Initialize the database connection
$database = new Database();
$dbConnection = $database->getConnection();

// Define routes
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
    $controller-> index();
});

$router->add('/logout', function() use ($dbConnection) {
    $controller = new LoginController($dbConnection);
    $controller->logoutAction();
});


// Handle the incoming URL
$requestedUrl = $_SERVER['REQUEST_URI'];
$controllerAction = $router->match($requestedUrl);

// Execute the matched route directly (call closure)
if ($controllerAction) {
    $controllerAction();
} else {
    echo "Page not found!";
}
?>
<!-- end of log in  ---------------------------------------------------------->