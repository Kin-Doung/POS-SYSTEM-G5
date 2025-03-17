<?php
require_once './Databases/database.php';
// class SettingModel {
//     private $db;

//     public function __construct() {
//         $this->db = new Database();
//     }
//     public function getAdminUsers() {
//         return $this->db->query("SELECT * FROM admin")->fetchAll();
//     }
//     public function createAdmin($data){
//         $query = "INSERT INTO admins (username, email, password, store_name, store_logo, language) 
//                   VALUES (:username, :email, :password, :store_name, :store_logo, :language)";
//         $stmt = $this->db->pdo($query);
//         $stmt->bindParam(':username', $data['username']);
//         $stmt->bindParam(':email', $data['email']);
//         $stmt->bindParam(':password', $data['password']);
//         $stmt->bindParam(':store_name', $data['store_name']);
//         $stmt->bindParam(':store_logo', $data['store_logo'], PDO::PARAM_LOB); // Store image as BLOB
//         $stmt->bindParam(':language', $data['language']);
//         return $stmt->execute();
//     }

//     // Get admin by ID
//     public function getAdmin($id)
//     {
//         $query = "SELECT * FROM admins WHERE id = :id";
//         $stmt = $this->db->pdo($query);
//         $stmt->bindParam(':id', $id);
//         $stmt->execute();
//         return $stmt->fetch(PDO::FETCH_ASSOC);
//     }

//     // Update admin
//     public function updateAdmin($id, $data)
//     {
//         $query = "UPDATE admins SET username = :username, email = :email, password = :password, 
//                   store_name = :store_name, store_logo = :store_logo, language = :language WHERE id = :id";
//         $stmt = $this->db->pdo($query);
//         $stmt->bindParam(':username', $data['username']);
//         $stmt->bindParam(':email', $data['email']);
//         $stmt->bindParam(':password', $data['password']);
//         $stmt->bindParam(':store_name', $data['store_name']);
//         $stmt->bindParam(':store_logo', $data['store_logo'], PDO::PARAM_LOB);
//         $stmt->bindParam(':language', $data['language']);
//         $stmt->bindParam(':id', $id);
//         return $stmt->execute();
//     }
    
// }



class SettingModel {
    private $pdo;

    public function __construct() {
        $this->pdo = new Database();
    }

    // Get all admin users
    public function getAdminUsers() {
        return $this->pdo->query("SELECT * FROM admin")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single admin user by ID
    public function getAdmin($id) {
        $query = "SELECT * FROM admins WHERE id = :id";
        $stmt = $this->pdo->query($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update admin information
    public function updateAdmin($id, $data) {
        $query = "UPDATE admins SET 
                    username = :username, 
                    email = :email, 
                    password = :password, 
                    store_name = :store_name, 
                    store_logo = :store_logo, 
                    language = :language 
                  WHERE id = :id";
                  
        $stmt = $this->pdo->query($query);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']); // Ensure passwords are hashed before storing
        $stmt->bindParam(':store_name', $data['store_name']);
        $stmt->bindParam(':store_logo', $data['store_logo'], PDO::PARAM_LOB);
        $stmt->bindParam(':language', $data['language']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

