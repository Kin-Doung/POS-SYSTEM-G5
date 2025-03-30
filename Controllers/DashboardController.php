<?php
require_once './Controllers/BaseController.php';

class DashboardController extends BaseController
{
    public function index()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the user is logged in
        if (!isset($_SESSION['username'])) {
            // User is not logged in: redirect to login page or show 404
            http_response_code(404); // Change this to a 404 error response
            include __DIR__ . '/../views/errors/404.php'; // Or redirect to login form
            exit();
        }
        
        // User is logged in, show the dashboard
        $this->Views('dashboards/list');
    }
}
