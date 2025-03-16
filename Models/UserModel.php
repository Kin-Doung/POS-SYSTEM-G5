<?php
require_once './Databases/database.php';

class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();
    }

    public function getUserByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }
}
?>
