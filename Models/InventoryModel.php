<?php
require_once 'Databases/database.php';

class InventoryModel {
    private $pdo;
    function __construct() {
        $this->pdo = new Database();
    }
    function getInventory()
    {
        $inventory = $this->pdo->query("SELECT * FROM inventory ORDER BY id DESC");
        return $inventory->fetchAll();
    }
    function getProduct()
    {
        $product = $this->pdo->query("SELECT * FROM products ORDER BY id DESC");
        return $product->fetchAll();
    }
    function addInventory($data)
    {
        $this->pdo->query("INSERT INTO inventory (product_name, image, quantity, price, added_date) 
            VALUES (:product_name, :image, :quantity, :price, :added_date)", [
            'product_name' => $data['product_name'],
            'image' => $data['image'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'added_date' => $data['added_date'],
        ]);
    }
    
    function getInventoryById($id)   
    {
        $stmt = $this->pdo->query("SELECT * FROM inventory WHERE id = :id", ['id' => $id]);
        $inventory = $stmt->fetch();
        return $inventory;
    }
    public function updateInventory($id, $data)
    {
        $this->pdo->query("UPDATE inventory SET product_name = :product_name, image = :image, price = :price, quantity = :quantity WHERE id = :id", [
            'product_name' => $data['product_name'],
            'image' => $data['image'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'id' => $id
        ]);
    }
    
    public function deleteInventory($id)
    {
        $this->pdo->query("DELETE FROM inventory WHERE id = :id", ['id' => $id]);
    }
    public function updateQuantity($id, $newQuantity)
    {
        $this->pdo->query("UPDATE inventory SET quantity = :quantity WHERE id = :id", [
            'quantity' => $newQuantity,
            'id' => $id
        ]);
    }
}
