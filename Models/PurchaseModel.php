<?php
require_once 'Databases/database.php';

class PurchaseModel {
    private $pdo;
    
    function __construct() {
        $this->pdo = new Database();
    }

    function getPurchase() {
        $stmt = $this->pdo->query("SELECT * FROM purchase ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    function createPurchase($data) {
        // Use prepare instead of query
        $stmt = $this->pdo->query("INSERT INTO purchase (product_name, image, quantity, price, purchase_date) 
            VALUES (:product_name, :image, :quantity, :price, :purchase_date)");
        $stmt->execute([
            'product_name' => $data['product_name'],
            'image' => $data['image'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'purchase_date' => $data['purchase_date'],
        ]);
    }

    function getPurchases($id) {
        // Use query instead of query for binding parameters
        $stmt = $this->pdo->query("SELECT * FROM purchase WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function updatePurchase($id, $data) {
        // Use query instead of query for binding parameters
        $stmt = $this->pdo->query("UPDATE purchase SET product_name = :product_name, image = :image, price = :price WHERE id = :id");
        $stmt->execute([
            'product_name' => $data['product_name'],
            'image' => $data['image'],
            'price' => $data['price'],
            'id' => $id
        ]);
    }

    public function deletePurchase($id) {
        // Use query instead of query for binding parameters
        $stmt = $this->pdo->query("DELETE FROM purchase WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function updateQuantity($id, $newQuantity) {
        // Use query instead of query for binding parameters
        $stmt = $this->pdo->query("UPDATE purchase SET quantity = :quantity WHERE id = :id");
        $stmt->execute([
            'quantity' => $newQuantity,
            'id' => $id
        ]);
    }
}
?>
