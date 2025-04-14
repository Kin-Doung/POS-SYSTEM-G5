<?php
class BaseController
{
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Generate CSRF token if not set
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            error_log("BaseController: Generated CSRF token: " . $_SESSION['csrf_token']);
        }
    }

    public function Views($view, $data = [])
    {
        extract($data);
        ob_start();
        require_once 'views/' . $view . '.php';
        $content = ob_get_clean();
        require_once 'views/layout.php';
    }
    
    public function redirect($uri)
    {
        header('Location: ' . $uri);
        exit();
    }
}