<?php
require_once 'Databases/database.php';

class ProductModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConnection();
    }

    // Fetch all products
    public function getProducts()
    {
        return $this->fetchAll("SELECT * FROM products ORDER BY id DESC");
    }

    // Fetch a product by ID
    public function getProductById($id)
    {
        return $this->fetchOne("SELECT * FROM products WHERE id = :id LIMIT 1", ['id' => $id]);
    }

    // Fetch all categories
    public function getCategories()
    {
        return $this->fetchAll("SELECT * FROM categories ORDER BY id DESC");
    }

    public function updatePrice($productId, $newPrice)
    {
        // Create a database connection
        $db = new Database();
        $pdo = $db->getConnection();

        // Prepare and execute the SQL query
        $stmt = $pdo->prepare("UPDATE products SET price = :price WHERE id = :id");
        $stmt->bindParam(':price', $newPrice);
        $stmt->bindParam(':id', $productId);
        return $stmt->execute();
    }

    // Fetch inventory details with product data
    public function getInventoryWithProductDetails()
    {
        $query = "
            SELECT 
                i.id AS inventory_id,
                i.product_name AS inventory_product_name,
                i.quantity,
                i.amount,
                i.total_price,
                i.expiration_date,
                i.image,
                c.name AS category_name
            FROM inventory i
            LEFT JOIN categories c ON i.category_id = c.id
            LIMIT 25;
        ";
        return $this->fetchAll($query);
    }

    // Create a new product
    public function createProduct($data)
    {
        $query = "INSERT INTO products (category_id, name, category_name, barcode, price, purchase_id, created_at, quantity, image) 
                  VALUES (:category_id, :name, :category_name, :barcode, :price, :purchase_id, NOW(), :quantity, :image)";
        return $this->executeQuery($query, $data);
    }

    // Check if a product exists by name and category
    public function getProductByNameAndCategory($productName, $categoryName)
    {
        $query = "
            SELECT * FROM products 
            WHERE name = :name AND category_id = 
            (SELECT id FROM categories WHERE name = :category_name LIMIT 1) 
            LIMIT 1";
        return $this->fetchOne($query, ['name' => $productName, 'category_name' => $categoryName]);
    }

    // Update product information from inventory
    public function updateProductFromInventory($id, $data)
    {
        $sql = "UPDATE products SET 
                    category_id = :category_id, 
                    category_name = :category_name, 
                    price = :price, 
                    quantity = :quantity, 
                    image = :image 
                WHERE id = :id";
        return $this->executeQuery($sql, [
            ':category_id' => $data['category_id'],
            ':category_name' => $data['category_name'],
            ':price' => $data['price'],
            ':quantity' => $data['quantity'],
            ':image' => $data['image'],
            ':id' => $id
        ]);
    }

    // Insert products from inventory into the products table
    public function insertProductsFromInventory()
    {
        $this->pdo->beginTransaction();
        try {
            $query = "
                SELECT 
                    i.product_name, 
                    c.id AS category_id, 
                    c.name AS category_name, 
                    i.amount AS price, 
                    i.quantity, 
                    i.image
                FROM inventory i
                JOIN categories c ON i.category_id = c.id;
            ";
            $inventoryItems = $this->fetchAll($query);
            foreach ($inventoryItems as $item) {
                $existingProduct = $this->getProductByNameAndCategory($item['product_name'], $item['category_name']);
                if ($existingProduct) {
                    $this->updateProductFromInventory($existingProduct['id'], $item);
                } else {
                    $this->createProduct($item);
                }
            }
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error inserting products: " . $e->getMessage());
            return false;
        }
    }

    public function updateProductPrice($productId, $newPrice)
    {
        try {
            // Fetch current price and quantity from the products table
            $query = "SELECT price, quantity FROM products WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product === false) {
                return false; // Product not found
            }
    
            // Get the old price and current quantity of the product
            $oldPrice = $product['price'];
            $quantity = $product['quantity'];
    
            // Update price history for tracking
            $historyQuery = "INSERT INTO price_history (product_id, old_price, new_price, changed_at) VALUES (:product_id, :old_price, :new_price, NOW())";
            $this->executeQuery($historyQuery, [
                ':product_id' => $productId,
                ':old_price' => $oldPrice,
                ':new_price' => $newPrice
            ]);
    
            // Update the product price in the products table
            $updateQuery = "UPDATE products SET price = :price WHERE id = :id";
            $this->executeQuery($updateQuery, [
                ':price' => $newPrice,
                ':id' => $productId
            ]);
    
            // Now subtract the quantity in inventory (i.e., subtract product quantity from inventory)
            // If the price change results in a product being moved to another state, this is where the logic comes in
            $inventoryUpdateQuery = "UPDATE inventory SET quantity = quantity - :quantity WHERE product_id = :product_id";
            $this->executeQuery($inventoryUpdateQuery, [
                ':quantity' => $quantity,
                ':product_id' => $productId
            ]);
    
            return true;
        } catch (Exception $e) {
            error_log("Error updating product price and inventory: " . $e->getMessage());
            return false;
        }
    }
    

    public function getPriceHistory($productId)
    {
        return $this->fetchAll("SELECT * FROM price_history WHERE product_id = :product_id ORDER BY changed_at DESC", ['product_id' => $productId]);
    }

    // Helper function to fetch all rows with optional parameters
    private function fetchAll($query, $params = [])
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Helper function to fetch a single row
    private function fetchOne($query, $params)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Helper function to execute a query (insert, update, delete)
    private function executeQuery($query, $params)
    {
        try {
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Error executing query: " . $e->getMessage());
            return false;
        }
    }

    public function deductInventoryQuantity($productId, $quantitySold)
{
    try {
        // Check if there is enough inventory
        $query = "SELECT quantity FROM inventory WHERE product_id = :product_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':product_id' => $productId]);
        $inventory = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$inventory || $inventory['quantity'] < $quantitySold) {
            return false; // Not enough stock
        }

        // Deduct the quantity
        $updateQuery = "UPDATE inventory SET quantity = quantity - :quantity WHERE product_id = :product_id";
        return $this->executeQuery($updateQuery, [
            ':quantity' => $quantitySold,
            ':product_id' => $productId
        ]);
    } catch (Exception $e) {
        error_log("Error updating inventory: " . $e->getMessage());
        return false;
    }
}

}


