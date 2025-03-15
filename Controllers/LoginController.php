<?php
session_start();
require_once __DIR__ . '/../Models/UserModel.php';

class LoginController {

    // Show the login form
    public function showLogin() {
        include __DIR__ . '/../Views/login_view.php';
    }

    // Process the login request
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = new UserModel();
            $user = $userModel->getUserByUsername($username);

            if (!$user) {
                $_SESSION['error'] = "Username not found!";
                header('Location: /login');
                exit();
            } elseif ($password === $user['password']) { // Plain text password check
                $_SESSION['username'] = $username;
                header('Location: /dashboard/list'); // ✅ Redirect to `/dashboard/list`
                exit();
            } else {
                $_SESSION['error'] = "Incorrect password!";
                header('Location: /login');
                exit();
            }
        }
    }

    // Handle logout
    public function logout() {
        session_destroy();
        header('Location: /login');
        exit();
    }
}
?>
