<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/UserModel.php';

class LoginController extends BaseController {
    public function showLogin() {
        // Log session state
        error_log("showLogin: Session username: " . ($_SESSION['username'] ?? 'none') . ", redirect_count: " . ($_SESSION['redirect_count'] ?? 0));

        // Check if user is authenticated and session is valid
        if (isset($_SESSION['username'])) {
            $userModel = new UserModel();
            $user = $userModel->getUserByUsername($_SESSION['username']);
            if ($user) {
                error_log("User {$_SESSION['username']} already logged in, redirecting to dashboard");
                header('Location: /dashboard');
                exit();
            } else {
                // Clear invalid session
                error_log("Invalid session for {$_SESSION['username']}, clearing session");
                unset($_SESSION['username']);
            }
        }

        // Generate CSRF token if not set
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            error_log("Generated new CSRF token: {$_SESSION['csrf_token']}");
        }

        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        error_log("Rendering login page, error: " . ($error ?? 'none'));
        include __DIR__ . '/../views/login_view.php';
    }

    public function processLogin() {
        error_log("processLogin: Method: {$_SERVER['REQUEST_METHOD']}, CSRF token: " . ($_POST['csrf_token'] ?? 'none'));

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Non-POST request to /login, redirecting to /");
            header('Location: /');
            exit();
        }

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            error_log("CSRF token mismatch, expected: {$_SESSION['csrf_token']}, received: " . ($_POST['csrf_token'] ?? 'none'));
            header('Location: /');
            exit();
        }

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Username and password are required';
            error_log("Empty username or password, redirecting to /");
            header('Location: /');
            exit();
        }

        $userModel = new UserModel();
        $user = $userModel->getUserByUsername($username);

        if (!$user) {
            $_SESSION['error'] = 'Invalid username or password';
            error_log("User $username not found, redirecting to /");
            header('Location: /');
            exit();
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            session_regenerate_id(true);
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            error_log("Login successful for $username, new CSRF token: {$_SESSION['csrf_token']}, redirecting to /dashboard");
            header('Location: /dashboard');
            exit();
        } else {
            $_SESSION['error'] = 'Invalid username or password';
            error_log("Password verification failed for $username");
            header('Location: /');
            exit();
        }
    }
}
?>