<?php
require_once './Databases/database.php';

class PurchaseModel {
    private $pdo;

    function __construct() {
        $this->pdo = new Database();
    }

    function getAllPurchases() {
        $stmt = $this->pdo->query("SELECT * FROM purchases ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    function createPurchase($data) {
        $stmt = $this->pdo->query("INSERT INTO purchases (product_name, image, quantity, price, purchase_date) 
            VALUES (:product_name, :image, :quantity, :price, :purchase_date)", [
            'product_name' => $data['product_name'],
            'image' => $data['image'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'purchase_date' => $data['purchase_date'],
        ]);
    }
    
    function getPurchaseById($id) {
        $stmt = $this->pdo->query("SELECT * FROM purchases WHERE id = :id", ['id' => $id]);
        $purchase = $stmt->fetch();
        return $purchase;
    }

    public function updatePurchase($id, $data) {
        $stmt = $this->pdo->query("UPDATE purchases SET product_name = :product_name, image = :image, price = :price WHERE id = :id", [
            'product_name' => $data['product_name'],
            'image' => $data['image'],
            'price' => $data['price'],
            'id' => $id
        ]);
    }

    public function deletePurchase($id) {
        $stmt = $this->pdo->query("DELETE FROM purchases WHERE id = :id", ['id' => $id]);
    }

    public function updateQuantity($id, $newQuantity) {
        $stmt = $this->pdo->query("UPDATE purchases SET quantity = :quantity WHERE id = :id", [
            'quantity' => $newQuantity,
            'id' => $id
        ]);
    }
}
?>
