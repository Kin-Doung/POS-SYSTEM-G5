<?php
require_once __DIR__ . '/BaseController.php';

class LogoutController extends BaseController {
    public function index() {
        // Clear all session data
        $_SESSION = [];
        
        // Destroy the session
        session_destroy();
        
        // Redirect to login page
        header('Location: /');
        exit();
    }
}
?>