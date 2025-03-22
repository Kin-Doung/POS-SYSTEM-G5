
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   



    // Get a single admin user by ID
    public function getAdmin($id)
    {
        $query = "SELECT * FROM admin WHERE id = :id";
        $stmt = $this->pdo->prepare($query); // Use prepare() instead of query()
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute(); // Execute the statement
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update admin information
    public function saveAdmin($data)
    {
        $sql = "INSERT INTO admin (username, email, password, store_name, store_logo, language) 
                VALUES (:username, :email, :password, :store_name, :store_logo, :language)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

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
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':store_name', $data['store_name']);
        $stmt->bindParam(':store_logo', $data['store_logo'], PDO::PARAM_LOB);
        $stmt->bindParam(':language', $data['language']);

        return $stmt->execute();
    }



    // Delete admin
    public function deleteAdmin($id)
    {
        $query = "DELETE FROM admin WHERE id = :id";
        $stmt = $this->pdo->prepare($query); // Use prepare() instead of query()
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // Execute the statement
    }
}
