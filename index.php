<?php
require_once './Routers/routers.php';
?>
<?php
// Start session only if not active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload classes (adjust paths as needed)
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $file = __DIR__ . '/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Parse URL
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Routing logic
if ($requestUri === '/history/fetchFilteredHistories' && $method === 'POST') {
    $controller = new HistoryController();
    $controller->fetchFilteredHistories();
} elseif ($requestUri === '/history/destroy' && $method === 'DELETE') {
    $controller = new HistoryController();
    $controller->destroy();
} else {
    // Handle other routes or 404
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
}
