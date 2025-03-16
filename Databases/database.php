<?php
class database
{
    private $pdo;

    function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "vc1_pos_system";

        try {
            $this->pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Method to get the PDO connection
    public function getConnection()
    {
        return $this->pdo;
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
?>
