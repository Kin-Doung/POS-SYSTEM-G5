<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
// Start output buffering
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


class DashboardController {
    public function showDashboardList() {
        if (!isset($_SESSION['username'])) {
            header('Location: /login'); // Redirect to login if not logged in
            exit();
        }
        include __DIR__ . '/../Views/dashboards/list.php';
    }
}
?>
