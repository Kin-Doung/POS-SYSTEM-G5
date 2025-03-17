<?php
require_once './Databases/database.php';

class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();  // Ensure we get the PDO instance
    }

    public function getUserByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE username = :username");  // Ensure the table is correct
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Fetch result as an associative array
    }
}
?>
