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
    function createInventory($data)
    {
        $categoryId = $data['category_id']; // Now use the category_id directly from the form

        $this->pdo->query("INSERT INTO inventory (image, product_name, quantity, amount, category_id, expiration_date) 
                           VALUES (:image, :product_name, :quantity, :amount, :category_id, :expiration_date)", [
            'image' => $data['image'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'amount' => $data['amount'],
            'category_id' => $categoryId, // Insert category_id into the inventory table
            'expiration_date' => $data['expiration_date'],
        ]);
    }

    // Get a single inventory item by ID
    function getInventorys($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM inventory WHERE id = :id", ['id' => $id]);
        return $stmt->fetch();
    }

    // ✅ New function to fetch an inventory item with category name
    function viewInventory($id)
    {
        $stmt = $this->pdo->query("
            SELECT inventory.*, categories.category_name 
            FROM inventory 
            LEFT JOIN categories ON inventory.category_id = categories.id 
            WHERE inventory.id = :id
        ", ['id' => $id]);

        return $stmt->fetch(); // Fetch only one row
    }

    // Update an inventory item
    function updateInventory($id, $data)
    {
        $this->pdo->query("UPDATE inventory 
                       SET image = :image, product_name = :product_name, quantity = :quantity, 
                           amount = :amount, category_id = :category_id, expiration_date = :expiration_date 
                       WHERE id = :id", [
            'image' => $data['image'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'amount' => $data['amount'],
            'category_id' => $data['category_id'],
            'expiration_date' => $data['expiration_date'],
            'id' => $id
        ]);
    }

    public function deleteItem($id)
    {
        try {
            // Ensure that the ID is numeric
            if (is_numeric($id)) {
                // Use query() method directly without prepare
                $sql = "DELETE FROM inventory WHERE id = $id"; // Use the ID directly in the SQL
                $this->pdo->query($sql);  // Execute the query
            } else {
                throw new Exception("Invalid ID"); // Throw error if ID is not valid
            }
        } catch (Exception $e) {
            // Handle the error if it occurs
            echo "Error deleting item: " . $e->getMessage();
        }
    }
    

    
    
}
