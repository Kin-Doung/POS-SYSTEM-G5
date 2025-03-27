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

    function getInventoryWithCategory()
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

        $categoryId = $data['category_id'];

        // Now use the category_id directly from the form

        $this->pdo->query("
        INSERT INTO inventory (image, product_name, quantity, amount, category_name, category_id, expiration_date, total_price) 
        VALUES (:image, :product_name, :quantity, :amount, :category_name, :category_id, :expiration_date, :total_price)
    ", [
            'image' => $data['image'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'amount' => $data['amount'],
            'category_name' => $data['category_name'], // Now include category_name in the query
            'category_id' => $categoryId,
            'expiration_date' => $data['expiration_date'],
            'total_price' => $data['total_price'],
        ]);
    }

    // Function to insert multiple inventory items at once
    public function createMultipleInventory($items)
    {
        try {
            $this->pdo->beginTransaction(); // Start the transaction

            $stmt = $this->pdo->query("INSERT INTO inventory (image, product_name, quantity, amount, category_id, category_name, expiration_date) 
                                         VALUES (:image, :product_name, :quantity, :amount, :category_id, :category_name, :expiration_date)");

            // Loop through each item and execute the prepared statement
            foreach ($items as $item) {
                $stmt->execute([
                    ':image' => $item['image'],
                    ':product_name' => $item['product_name'],
                    ':quantity' => $item['quantity'],
                    ':amount' => $item['amount'],
                    ':category_id' => $item['category_id'],
                    ':category_name' => $item['category_name'],
                    ':expiration_date' => $item['expiration_date']
                ]);
            }

            $this->pdo->commit(); // Commit the transaction
            return true; // Return true if everything went well
        } catch (Exception $e) {
            $this->pdo->rollBack(); // Rollback the transaction if there's an error
            echo "Error: " . $e->getMessage(); // Show error message
            return false; // Return false if there was an error
        }
    }


    // Edit a category name
    public function updateCategory($categoryId, $newCategoryName)
    {
        $sql = "UPDATE categories SET name = :new_category_name WHERE id = :category_id";

        $params = [
            ':new_category_name' => $newCategoryName,
            ':category_id' => $categoryId
        ];

        $this->pdo->query($sql, $params);
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
            SELECT inventory.*, categories.name
            FROM inventory 
            LEFT JOIN categories ON inventory.category_id = categories.id 
            WHERE inventory.id = :id
        ", ['id' => $id]);

        return $stmt->fetch(); // Fetch only one row
    }

    // Update an inventory item
    public function updateInventory($id, $data)
    {
        $sql = "UPDATE inventory SET 
                category_id = :category_id,
                product_name = :product_name,
                quantity = :quantity,
                amount = :amount,
                category_name = :category_name,
                total_price = :total_price,
                expiration_date = :expiration_date,
                image = :image
                WHERE id = :id";
    
        $params = [
            ':category_id' => $data['category_id'],  // Update category_id
            ':product_name' => $data['product_name'],
            ':quantity' => $data['quantity'],
            ':amount' => $data['amount'],
            ':category_name' => $data['category_name'],
            ':total_price' => $data['total_price'],
            ':expiration_date' => $data['expiration_date'],
            ':image' => $data['image'],
            ':id' => $id
        ];
    
        $this->pdo->query($sql, $params);
    }
    

    // Get a category by its ID
    public function getCategoryById($categoryId)
    {
        $stmt = $this->pdo->query("SELECT * FROM categories WHERE id = :category_id", ['category_id' => $categoryId]);
        return $stmt->fetch(); // Fetch the category
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
