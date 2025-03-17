<?php
class Database {
    private $pdo;

    public function __construct() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "vc1_pos_system";  // Change the DB name if needed

        try {
            $this->pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Set error mode to exception
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getConnection() {
        return $this->pdo;  // Return the PDO instance for external use
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
?>
