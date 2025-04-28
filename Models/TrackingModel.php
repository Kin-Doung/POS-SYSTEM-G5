<?php
require_once './Databases/database.php';
class TrackingModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database();
    }

    function getInventory()
    {
        $tracking = $this->pdo->query("SELECT * FROM inventory ORDER BY id DESC");
        return $tracking->fetchAll();
    }

    function getCategory()
    {
        $tracking = $this->pdo->query("SELECT * FROM categories ORDER BY id DESC");
        return $tracking->fetchAll();
    }

    function createInventory(array $data): int
    {
        $defaults = [
            'image' => null,
            'product_name' => '',
            'category_id' => null,
            'quantity' => 0,
            'amount' => 0,
            'category_name' => '',
            'expiration_date' => null,
            'total_price' => 0
        ];
        $data = array_merge($defaults, $data);

        try {
            $stmt = $this->pdo->getConnection()->prepare("
                INSERT INTO inventory (image, product_name, category_id, quantity, amount, category_name, expiration_date, total_price)
                VALUES (:image, :product_name, :category_id, :quantity, :amount, :category_name, :expiration_date, :total_price)
            ");
            $stmt->bindParam(':image', $data['image']);
            $stmt->bindParam(':product_name', $data['product_name']);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':amount', $data['amount']);
            $stmt->bindParam(':category_name', $data['category_name']);
            $stmt->bindParam(':expiration_date', $data['expiration_date']);
            $stmt->bindParam(':total_price', $data['total_price']);

            $stmt->execute();
            return (int)$this->pdo->getConnection()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Insert error: " . $e->getMessage());
            throw new Exception("Failed to create tracking record: " . $e->getMessage());
        }
    }
    
}
