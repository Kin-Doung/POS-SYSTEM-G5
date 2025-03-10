<?php
class LoginController {
    private $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function showLogin() {
        // Show the login view (HTML form)
        include 'Views/login_view.php';
    }

    public function loginAction() {
        // Check if form data is submitted
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Prepare and execute the query to find the user
            $query = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->dbConnection->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // Check if the user exists
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify the password
                if ($password === $user['password']) {
                    // Successful login, store session or set cookies
                    session_start();
                    $_SESSION['user'] = $user;  // Store user data in session
                    header("Location: /home");
                    exit();
                } else {
                    // Invalid password
                    $this->showError("Invalid username or password!");
                }
            } else {
                // Invalid username
                $this->showError("Invalid username or password!");
            }
        } else {
            // Missing username or password
            $this->showError("Please enter both username and password.");
        }
    }

    public function logoutAction() {
        // Destroy the session to log out the user
        session_start();
        session_unset();    // Remove all session variables
        session_destroy();  // Destroy the session
        header("Location: /");  // Redirect to login page after logging out
        exit();
    }

    public function showError($message) {
        // Set the error message in the session
        session_start();
        $_SESSION['error_message'] = $message;
    
        // Redirect to the login page again
        $this->showLogin();
    }
    
}
?>
