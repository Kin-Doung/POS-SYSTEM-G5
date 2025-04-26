<?php
// File: CategoryModel.php
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
        try {
            $stmt = $this->pdo->getConnection()->prepare("SELECT * FROM categories ORDER BY id DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Get categories error: ' . $e->getMessage());
            return [];
        }
    }

    function createCategory($data)
    {
        try {
            $stmt = $this->pdo->getConnection()->prepare("INSERT INTO categories (name) VALUES (:name)");
            $stmt->execute(['name' => $data['name']]);
        } catch (PDOException $e) {
            error_log('Create category error: ' . $e->getMessage());
        }
    }

    public function getCategoryById($categoryId)
    {
        try {
            $stmt = $this->pdo->getConnection()->prepare("SELECT * FROM categories WHERE id = :id");
            $stmt->execute(['id' => $categoryId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Get category by ID error: ' . $e->getMessage());
            return false;
        }
    }

    public function getCategoryByName($name)
    {
        try {
            $stmt = $this->pdo->getConnection()->prepare("SELECT * FROM categories WHERE name = :name LIMIT 1");
            $stmt->execute(['name' => $name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Get category by name error: ' . $e->getMessage());
            return false;
        }
    }

    function updateCategory($id, $data)
    {
        try {
            $stmt = $this->pdo->getConnection()->prepare("UPDATE categories SET name = :name WHERE id = :id");
            $stmt->execute([
                'name' => $data['name'],
                'id' => $id
            ]);
            $success = $stmt->rowCount() > 0;
            if (!$success) {
                error_log('No rows updated for category ID: ' . $id);
            }
            return $success;
        } catch (PDOException $e) {
            error_log('Update category error: ' . $e->getMessage());
            return false;
        }
    }

    public function hasProducts($categoryId)
    {
        try {
            $stmt = $this->pdo->getConnection()->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
            $stmt->execute(['id' => (int)$categoryId]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log('Check products error: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteCategory($id)
    {
        $id = (int)$id;
        $pdo = $this->pdo->getConnection();
        try {
            $pdo->beginTransaction();

            $stmt1 = $pdo->prepare("DELETE r FROM reports r INNER JOIN products p ON r.product_id = p.id WHERE p.category_id = :id");
            $stmt1->execute(['id' => $id]);

            $stmt2 = $pdo->prepare("DELETE FROM products WHERE category_id = :id");
            $stmt2->execute(['id' => $id]);

            $stmt3 = $pdo->prepare("DELETE FROM categories WHERE id = :id");
            $stmt3->execute(['id' => $id]);

            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('Delete category error: ' . $e->getMessage());
            return false;
        }
    }
}