

<?php
class Database
{
    private $pdo;

    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "vc1_pos_system";  // Change the DB name if needed

        try {
            // Set up the PDO connection
            $this->pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Set error mode to exception
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Method to return the PDO instance
    public function getConnection()
    {
        return $this->pdo;
    }
    public function prepare($sql)
    {
        return $this->pdo->prepare($sql); // For parameterized queries
    }

    // General query execution method
    // In Databases/database.php
    public function query($sql, $params = [])
    {
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
    }





    // Start a transaction
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    // Commit the transaction
    public function commit()
    {
        $this->pdo->commit();
    }

    // Rollback the transaction
    public function rollBack()
    {
        $this->pdo->rollBack();
    }
}
