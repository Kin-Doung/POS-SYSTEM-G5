<?php
class BaseController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            error_log("BaseController: Generated CSRF token: " . $_SESSION['csrf_token']);
        }
    }
    protected function isAuthenticated()
    {
        if (!isset($_SESSION['username'])) {
            $_SESSION['error'] = 'Please log in to access this page.';
            header('Location: /');
            exit();
        }
    }

    public function Views($view, $data = [])
    {
        extract($data);
        $baseDir = realpath(__DIR__ . '/..');
        $viewPath = $baseDir . '/views/' . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php';
        $layoutPath = $baseDir . '/views/layout.php';

        error_log("Loading view: $viewPath");

        ob_start();
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            error_log("View not found: $viewPath");
            http_response_code(500);
            echo "View not found: $view";
            exit();
        }
        $content = ob_get_clean();

        error_log("Loading layout: $layoutPath");
        if (file_exists($layoutPath)) {
            require_once $layoutPath;
        } else {
            error_log("Layout not found: $layoutPath");
            echo $content;
        }
    }

    public function redirect($uri)
    {
        header('Location: ' . $uri);
        exit();
    }
}
