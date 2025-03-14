<?php
require_once __DIR__ . '/../Databases/database.php';

class PurchaseModel {
    private $pdo;
    function __construct() {
        $this->pdo = new Database();
    }
    function getPurchase()
    {
        $users = $this->pdo->query("SELECT * FROM purchases ORDER BY id DESC");
        return $users->fetchAll();
    }
    function createPurchase($data)
    {
        $this->pdo->query("INSERT INTO purchases ( product_name, image, quantity, price, purchase_date) VALUES ( :product_name,:image, :quantity, :price, purchase_date)", [
            'image' => $data['image'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'purchase_date' => $data['purchase_date'],
        ]);
    }
    function getPurchases($id)   
    {
        $stmt = $this->pdo->query("SELECT * FROM purchases WHERE id = :id", ['id' => $id]);
        $purchase = $stmt->fetch();
        return $purchase;
    }
}

