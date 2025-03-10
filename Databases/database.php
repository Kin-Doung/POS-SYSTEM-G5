<?php
class Database {
    private $host = 'localhost';
    private $dbName = 'usershop';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8';
    private $connection;

    public function getConnection() {
        if ($this->connection == null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";
                $this->connection = new PDO($dsn, $this->username, $this->password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return $this->connection;
    }
}
?>
