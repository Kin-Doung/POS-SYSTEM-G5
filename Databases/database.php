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

    // General query execution method
    // Database.php
    public function query($sql, $params = [])
    {
        // Prepare the SQL statement
        $stmt = $this->pdo->prepare($sql);

        // Execute the query with the provided parameters
        $stmt->execute($params);

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
