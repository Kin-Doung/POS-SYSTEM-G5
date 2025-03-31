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

    public function getCategoryById($categoryId)
    {
        $stmt = $this->pdo->getConnection()->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $categoryId]);
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
    public function deleteCategory($id)
{
    // Sanitize the id to ensure it is an integer (this is important for security)
    $id = (int)$id;

    // Directly inject the id into the query string
    $query = "DELETE FROM categories WHERE id = $id";

    // Execute the query
    if ($this->pdo->query($query)) {
        return true;
    } else {
        return false;
    }
}

    
    
}
