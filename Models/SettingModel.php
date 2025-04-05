<?php
require_once './Databases/database.php';

class SettingModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConnection(); // Get the PDO instance
    }

    // Get all admin users
    public function getAdminUsers()
    {
        $stmt = $this->pdo->query("SELECT * FROM admin");
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all admin data as associative array
    }

    // Get a single admin user by ID
    public function getAdmin($id)
    {
        $query = "SELECT * FROM admin WHERE id = :id";
        $stmt = $this->pdo->prepare($query); // Use prepare() for safer queries
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind ID safely
        $stmt->execute(); // Execute the statement
        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch single result as associative array
    }

    // Save a new admin user to the database
    public function saveAdmin($data)
    {
        $sql = "INSERT INTO admin (username, email, password, store_name, store_logo, language) 
                VALUES (:username, :email, :password, :store_name, :store_logo, :language)";
        $stmt = $this->pdo->prepare($sql);

        // Bind the parameters safely
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':store_name', $data['store_name']);
        $stmt->bindParam(':store_logo', $data['store_logo'], PDO::PARAM_LOB); // Bind the logo as LOB (Large Object)
        $stmt->bindParam(':language', $data['language']);

        return $stmt->execute(); // Execute the query and return the result
    }

    // Update an admin user
    public function updateAdmin($data)
    {
        $query = "UPDATE admin SET 
                    username = :username, 
                    email = :email, 
                    password = :password, 
                    store_name = :store_name, 
                    store_logo = :store_logo, 
                    language = :language
                  WHERE id = :id";

        $stmt = $this->pdo->prepare($query);

        // Bind all parameters safely
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':store_name', $data['store_name']);
        $stmt->bindParam(':store_logo', $data['store_logo'], PDO::PARAM_LOB); // Store the logo as LOB
        $stmt->bindParam(':language', $data['language']);

        return $stmt->execute(); // Execute the update query
    }
 





    // Delete an admin user
    public function deleteAdmin($id)
    {
        $query = "DELETE FROM admin WHERE id = :id";
        $stmt = $this->pdo->prepare($query); // Use prepare() for security
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind ID safely
        return $stmt->execute(); // Execute the delete query
    }
}
