<?php
require_once 'Databases/database.php';

class PurchaseModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database();
    }

    // Function to fetch all purchases
    function getPurchase()
    {
        $purchase = $this->pdo->query("SELECT * FROM purchase ORDER BY id DESC");
        return $purchase->fetchAll();
    }

    // Function to fetch all categories
    function getCategory()
    {
        // Query to get categories from the categories table
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY id DESC");
        $categories = $stmt->fetchAll();  // Fetch all categories as an associative array
        return $categories;
    }
    

    
    // Function to create a new purchase
    function createPurchase($data)
    {
        // Insert the purchase data into the purchase table
        $this->pdo->query("INSERT INTO purchase (product_name, image, quantity, price, purchase_date, category_id) 
            VALUES (:product_name, :image, :quantity, :price, :purchase_date, :category_id)", [
            'product_name' => $data['product_name'],
            'image' => $data['image'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'purchase_date' => $data['purchase_date'],
            'category_id' => $data['category_id'],  // Add category_id
        ]);
    }

    // Function to fetch a specific purchase by ID
    function getPurchases($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM purchase WHERE id = :id", ['id' => $id]);
        $purchase = $stmt->fetch();
        return $purchase;
    }

    // Function to update a purchase
    public function updatePurchase($id, $data)
    {
        // First query to update the purchase table
        $this->pdo->query("UPDATE purchase SET product_name = :product_name, image = :image, price = :price, category_id = :category_id WHERE id = :id", [
            'product_name' => $data['product_name'],
            'image' => $data['image'],
            'price' => $data['price'],
            'category_id' => $data['category_id'],  // Ensure category_id is updated
            'id' => $id
        ]);

        // Second query to update the products table
        $this->pdo->query("UPDATE products SET name = :name, price = :price WHERE id = :id", [
            'name' => $data['product_name'], // Ensure to pass the correct product_name
            'price' => $data['price'],
            'id' => $id
        ]);
    }

    // Function to delete a purchase
    public function deletePurchase($id)
    {
        $this->pdo->query("DELETE FROM purchase WHERE id = :id", ['id' => $id]);
    }

    // Function to update quantity for a purchase
    public function updateQuantity($id, $newQuantity)
    {
        $this->pdo->query("UPDATE purchase SET quantity = :quantity WHERE id = :id", [
            'quantity' => $newQuantity,
            'id' => $id
        ]);
    }
}
