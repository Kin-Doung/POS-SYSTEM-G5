<?php
require_once './Databases/database.php';

class InventoryModel
{
    private $pdo;

    public function __construct()
    {
        // Use the Database class to get the PDO instance
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    // Get all inventory items
    public function getInventory()
    {
        $stmt = $this->pdo->query("SELECT * FROM inventory ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getCategory()
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getInventoryWithCategory()
    {
        // Fetch inventory with category name
        $stmt = $this->pdo->query("
            SELECT inventory.*, categories.name 
            FROM inventory 
            LEFT JOIN categories ON inventory.category_id = categories.id 
            ORDER BY inventory.id DESC
        ");
        return $stmt->fetchAll();
    }

    // Create a new inventory item
    public function createInventory($data)
    {
        // Fetch the category name based on category_id using prepared statement
        $stmt = $this->pdo->prepare("SELECT name FROM categories WHERE id = :id");
        $stmt->execute(['id' => $data['category_id']]);
        $category = $stmt->fetch();
        $category_name = $category ? $category['name'] : null;

        // Insert inventory data along with category name
        $stmt = $this->pdo->prepare("
            INSERT INTO inventory (image, product_name, quantity, amount, category_id, category_name, expiration_date, total_price) 
            VALUES (:image, :product_name, :quantity, :amount, :category_id, :category_name, :expiration_date, :total_price)
        ");

        $stmt->execute([
            'image' => $data['image'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'amount' => $data['amount'],
            'category_id' => $data['category_id'],
            'category_name' => $category_name,
            'expiration_date' => $data['expiration_date'],
            'total_price' => $data['total_price'],
        ]);
    }

    // Update an inventory item
    public function updateInventory($id, $data)
    {
        $stmt = $this->pdo->prepare("SELECT category_name FROM categories WHERE id = :id");
        $stmt->execute(['id' => $data['category_id']]);
        $category = $stmt->fetch();
        $category_name = $category ? $category['category_name'] : null;

        $stmt = $this->pdo->prepare("UPDATE inventory SET 
            category_id = :category_id,
            category_name = :category_name,
            product_name = :product_name,
            quantity = :quantity,
            amount = :amount,
            total_price = :total_price,
            expiration_date = :expiration_date,
            image = :image
            WHERE id = :id");

        $stmt->execute([
            ':category_id' => $data['category_id'],
            ':category_name' => $category_name,
            ':product_name' => $data['product_name'],
            ':quantity' => $data['quantity'],
            ':amount' => $data['amount'],
            ':total_price' => $data['total_price'],
            ':expiration_date' => $data['expiration_date'],
            ':image' => $data['image'],
            ':id' => $id
        ]);
    }

    // Get a single inventory item by ID
    public function getInventorys($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM inventory WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Get inventory item with category name
    public function viewInventory($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT inventory.*, categories.category_name 
            FROM inventory 
            LEFT JOIN categories ON inventory.category_id = categories.id 
            WHERE inventory.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(); // Fetch only one row
    }

    // Delete an inventory item
    public function deleteItem($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM inventory WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
