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
    public function getCategoryByName($name)
    {
        $query = "SELECT * FROM categories WHERE name = :name LIMIT 1";
        $stmt = $this->pdo->getConnection()->prepare($query);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getCategorys($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM categories WHERE id = :id", ['id' => $id]);
        $category = $stmt->fetch();
        return $category;
    }

    function updateCategory($id, $data)
    {
        $this->pdo->query("UPDATE categories SET name = :name WHERE id = :id", [
            'name' => $data['name'],
            'id' => $id
        ]);
    }


    public function hasProducts($categoryId)
    {
        $stmt = $this->pdo->getConnection()->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
        $stmt->execute(['id' => (int)$categoryId]);
        return $stmt->fetchColumn() > 0;
    }
    public function deleteCategory($id)
    {
        $id = (int)$id;
        $pdo = $this->pdo->getConnection();
        try {
            $pdo->beginTransaction();

            // Delete reports linked to products in this category
            $stmt1 = $pdo->prepare("DELETE r FROM reports r INNER JOIN products p ON r.product_id = p.id WHERE p.category_id = :id");
            $stmt1->execute(['id' => $id]);

            // Delete products in this category
            $stmt2 = $pdo->prepare("DELETE FROM products WHERE category_id = :id");
            $stmt2->execute(['id' => $id]);

            // Delete the category
            $stmt3 = $pdo->prepare("DELETE FROM categories WHERE id = :id");
            $stmt3->execute(['id' => $id]);

            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Delete failed: " . $e->getMessage());
            return false;
        }
    }
}