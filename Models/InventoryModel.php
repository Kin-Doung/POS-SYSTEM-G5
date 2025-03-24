<?php
require_once './Databases/database.php';

class InventoryModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database();
    }

    // Get all inventory items
    function getInventory()
    {
        $inventory = $this->pdo->query("SELECT * FROM inventory ORDER BY id DESC");
        return $inventory->fetchAll();
    }
    function getCategory()
    {
        $inventory = $this->pdo->query("SELECT * FROM categories ORDER BY id DESC");
        return $inventory->fetchAll();
    }

    public function getInventoryWithCategory()
    {
        $inventory = $this->pdo->query("
            SELECT inventory.*, categories.category_name 
            FROM inventory 
            LEFT JOIN categories ON inventory.category_id = categories.id 
            ORDER BY inventory.id DESC
        ");
        return $inventory->fetchAll();
    }
    // Create a new inventory item
    // Create a new inventory item
    function createInventory($data)
    {
        $categoryId = $data['category_id'];
        $totalPrice = $data['quantity'] * $data['amount']; // Calculate total price
    
        $stmt = $this->pdo->query("INSERT INTO inventory (image, product_name, quantity, amount, category_id, expiration_date, total_price) 
                                    VALUES (:image, :product_name, :quantity, :amount, :category_id, :expiration_date, :total_price)");
        $stmt->execute([
            'image' => $data['image'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'amount' => $data['amount'],
            'category_id' => $categoryId, 
            'expiration_date' => $data['expiration_date'],
            'total_price' => $totalPrice,
        ]);
    }
    
    function getInventorys($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM inventory WHERE id = :id", ['id' => $id]);
        $inventory = $stmt->fetch();
        return $inventory;
    }


    // Update an inventory item
    function updateInventory($id, $data)
    {
        $totalPrice = $data['quantity'] * $data['amount']; // Recalculate total price
    
        $stmt = $this->pdo->query("UPDATE inventory 
                                   SET image = :image, product_name = :product_name, quantity = :quantity, 
                                       amount = :amount, category_id = :category_id, expiration_date = :expiration_date,
                                       total_price = :total_price
                                   WHERE id = :id");
        $stmt->execute([
            'image' => $data['image'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'amount' => $data['amount'],
            'category_id' => $data['category_id'],
            'expiration_date' => $data['expiration_date'],
            'total_price' => $totalPrice,
            'id' => $id
        ]);
    }
    
    



    // Delete an inventory item by ID
    public function deleteItem($id)
    {
        $stmt = $this->pdo->query("DELETE FROM inventory WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}