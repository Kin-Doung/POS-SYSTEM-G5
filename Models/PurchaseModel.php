<?php
require_once './Databases/database.php';

class PurchaseModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database();
    }

    public function getConnection()
    {
        return $this->pdo->getConnection();
    }

    // Get all categories
    function getCategories()
    {
        $stmt = $this->pdo->getConnection()->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all purchases
    public function getPurchases()
    {
        $sql = "SELECT p.id, p.image_path, p.category_id, c.name AS category_name, p.product_name 
                FROM purchase p
                JOIN categories c ON p.category_id = c.id";
        $stmt = $this->pdo->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert a new product
    public function insertProduct($imagePath, $category_id, $product_name = null)
    {
        try {
            $stmt = $this->pdo->getConnection()->prepare("
                INSERT INTO purchase (image_path, category_id, product_name) 
                VALUES (:image_path, :category_id, :product_name)
            ");
            $stmt->bindParam(':image_path', $imagePath);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_name', $product_name);
            $stmt->execute();
            return $this->pdo->getConnection()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error inserting product: " . $e->getMessage());
            throw new Exception("Error inserting product: " . $e->getMessage());
        }
    }

    // Delete a purchase
    function deletePurchase($id)
    {
        try {
            $stmt = $this->pdo->getConnection()->prepare("DELETE FROM purchase WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting purchase: " . $e->getMessage());
            throw new Exception("Error deleting purchase: " . $e->getMessage());
        }
    }

    // Transaction functions
    public function startTransaction()
    {
        $this->pdo->getConnection()->beginTransaction();
    }

    public function commitTransaction()
    {
        $this->pdo->getConnection()->commit();
    }

    public function rollBackTransaction()
    {
        $this->pdo->getConnection()->rollBack();
    }
}
?>
