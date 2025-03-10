<?php
require_once './Databases/database.php';

class UserModel
{
    private $pdo;
    private $db;

    // Constructor accepts a PDO connection
    public function __construct($dbConnection = null) {
        if ($dbConnection) {
            $this->db = $dbConnection;
        } else {
            $this->pdo = new Database();
        }
    }
    // Function to get a user by username
    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Function to validate a password
    public function validatePassword($storedPassword, $inputPassword) {
        return password_verify($inputPassword, $storedPassword); 
    }
}
?>
