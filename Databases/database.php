<?php
class Database
{
    private $pdo;

    public function __construct()
    {
        // Get database configuration from environment variables
        $servername = getenv('DB_HOST') ?: 'localhost';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: '';
        $dbname = getenv('DB_NAME') ?: 'vc1_pos_system';
        $port = getenv('DB_PORT') ?: '3306';

        try {
            $dsn = "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4";
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            error_log("Database connection established to $dbname on $servername:$port");
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            http_response_code(500);
            echo "Database connection failed. Please check server logs.";
            exit();
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $key => $value) {
                if (is_int($value)) {
                    $stmt->bindValue(is_numeric($key) ? $key + 1 : $key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(is_numeric($key) ? $key + 1 : $key, $value, PDO::PARAM_STR);
                }
            }

            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query failed: $sql - " . $e->getMessage());
            throw $e;
        }
    }

    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function commit()
    {
        $this->pdo->commit();
    }

    public function rollBack()
    {
        $this->pdo->rollBack();
    }
}