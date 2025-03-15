<?php
require_once __DIR__ . '/../Databases/database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }
}
?>
