<?php
require_once 'Databases/database.php'; // Ensure correct path

class InventoryModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database(); // Get the PDO connection
    }

    function getInventory()
    {
        $stmt = $this->pdo->query("SELECT * FROM inventory ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function addInventory($data)
    {
        try {
            $stmt = $this->pdo->query("INSERT INTO inventory (image, product_name, product_id, quantity, price, amount, expiration_date) 
                VALUES (:image, :product_name, :product_id, :quantity, :price, :amount, :expiration_date)");
    
            $stmt->execute([
                ':image' => $data['image'],
                ':product_name' => $data['product_name'],
                ':product_id' => $data['product_id'], // Add this line
                ':quantity' => $data['quantity'],
                ':price' => $data['price'],
                ':amount' => $data['amount'],
                ':expiration_date' => $data['expiration_date']
            ]);
    
            echo "Record added successfully!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    

    function getInventoryById($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM inventory WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateInventory($id, $data)
    {
        $stmt = $this->pdo->query("UPDATE inventory 
            SET image = :image, product_name = :product_name, quantity = :quantity 
            WHERE id = :id");
        $stmt->execute([
            ':image' => $data['image'],
            ':product_name' => $data['product_name'],
            ':quantity' => $data['quantity'],
            ':id' => $id
        ]);
    }

    public function deleteInventory($id)
    {
        $stmt = $this->pdo->query("DELETE FROM inventory WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function updateQuantity($id, $newQuantity)
    {
        $stmt = $this->pdo->query("UPDATE inventory SET quantity = :quantity WHERE id = :id");
        $stmt->execute([
            ':quantity' => $newQuantity,
            ':id' => $id
        ]);
    }
}
