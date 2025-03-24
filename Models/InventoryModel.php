<?php
require_once './Databases/database.php';

class InventoryModel {
    private $db;

    public function __construct() {
        // Create an instance of the Database class and get the connection
        $this->db = (new Database())->getConnection();  // Directly get the connection
    }

    // Add new inventory item
    public function addInventory($data) {
        try {
            // Prepare SQL query to insert new inventory item
            $sql = "INSERT INTO inventory (product_name, image, quantity, amount, expiration_date) 
                    VALUES (:product_name, :image, :quantity, :amount, :expiration_date)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_name', $data['product_name']);
            $stmt->bindParam(':image', $data['image']);
            $stmt->bindParam(':quantity', $data['quantity']);
            $stmt->bindParam(':amount', $data['amount']);
            $stmt->bindParam(':expiration_date', $data['expiration_date']);
            
            $stmt->execute(); // Execute the query
        } catch (PDOException $e) {
            throw new Exception("Failed to add inventory item: " . $e->getMessage());
        }
    }

    // Get all inventory items
    public function getAllInventory() {
        try {
            $sql = "SELECT * FROM inventory";
            $stmt = $this->db->query($sql);  // Use the query method in Database class
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch all records as an associative array
        } catch (PDOException $e) {
            throw new Exception("Failed to retrieve inventory items: " . $e->getMessage());
        }
    }

    // Get single inventory item by ID (for edit)
    public function getInventoryById($id) {
        try {
            $sql = "SELECT * FROM inventory WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);  // Fetch a single record
        } catch (PDOException $e) {
            throw new Exception("Failed to retrieve inventory item: " . $e->getMessage());
        }
    }

    // Update an inventory item
    public function updateInventory($data) {
        try {
            $sql = "UPDATE inventory 
                    SET product_name = :product_name, image = :image, quantity = :quantity, 
                        amount = :amount, expiration_date = :expiration_date 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':product_name', $data['product_name']);
            $stmt->bindParam(':image', $data['image']);
            $stmt->bindParam(':quantity', $data['quantity']);
            $stmt->bindParam(':amount', $data['amount']);
            $stmt->bindParam(':expiration_date', $data['expiration_date']);
            $stmt->bindParam(':id', $data['id']);
            
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to update inventory item: " . $e->getMessage());
        }
    }

    // Delete an inventory item
    public function deleteInventory($id) {
        try {
            $sql = "DELETE FROM inventory WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to delete inventory item: " . $e->getMessage());
        }
    }

    public function getItemById($id)
    {
        // Query to get item by ID
        $stmt = $this->db->prepare("SELECT * FROM inventory WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Return the item data
    }
}
?>
