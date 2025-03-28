<?php
require_once './Databases/database.php';

class CategoryModel
{
    private $pdo;
    function __construct()
    {
        $this->pdo = new Database();
    }
    function getCategory()
    {
        $category = $this->pdo->query("SELECT * FROM categories ORDER BY id DESC");
        return $category->fetchAll();
    }

    function createCategory($data)
    {
        $this->pdo->query("INSERT INTO categories (name) VALUES (:name)", [
            'name' => $data['name'],
        ]);
    }

    function getCategoryById($id)
    {
        $db = new Database(); // Create Database instance
        $stmt = $db->query("SELECT name FROM categories WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    

    function getCategorys($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM categories WHERE id = :id", ['id' => $id]);
        $category = $stmt->fetch();
        return $category;
    }

    public function getCategoryByName($name)
{
    $query = "SELECT * FROM categories WHERE name = :name LIMIT 1";
    $stmt = $this->pdo->query($query);
    $stmt->execute(['name' => $name]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    function updateCategory($id, $data)
    {
        $this->pdo->query("UPDATE categories SET name = :name WHERE id = :id", [
            'name' => $data['name'],
            'id' => $id
        ]);
    }
    function deleteCategory($id)
    {
        $stmt = $this->pdo->query("DELETE FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return true;  // Success
        } else {
            echo "Error: " . implode(", ", $stmt->errorInfo());
            return false;  // Failure
        }
    }
    
}
