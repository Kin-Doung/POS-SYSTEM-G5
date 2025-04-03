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
        $query = "INSERT INTO products (category_id, name, barcode, price, purchase_id, created_at, quantity, image) 
                  VALUES (:category_id, :name, :barcode, :price, :purchase_id, NOW(), :quantity, :image)";
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
                    price = :price, 
                    quantity = :quantity, 
                    image = :image 
                WHERE id = :id";
        return $this->executeQuery($sql, [
            ':category_id' => $data['category_id'],
            ':price' => $data['price'],
            ':quantity' => $data['quantity'],
            ':image' => $data['image'],
            ':id' => $id
        ]);
    }

    public function updateProductPrice($productId, $newPrice)
    {
        try {
            $query = "SELECT price FROM products WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product === false) {
                return false;
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
            return $this->executeQuery($updateQuery, [
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
            error_log("Error updating product price: " . $e->getMessage());
            return false;
        }
    }

    public function getPriceHistory($productId)
    {
        return $this->fetchAll("SELECT * FROM price_history WHERE product_id = :product_id ORDER BY changed_at DESC", ['product_id' => $productId]);
    }

    private function fetchAll($query, $params = [])
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchOne($query, $params)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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
    public function deductInventoryAndUpdateProduct($productId, $quantityToBuy)
    {
        try {
            $this->pdo->beginTransaction();
    
            // Step 1: Check and deduct inventory quantity
            $inventoryQuery = "SELECT quantity FROM inventory WHERE product_id = :product_id";
            $stmt = $this->pdo->prepare($inventoryQuery);
            $stmt->execute([':product_id' => $productId]);
            $inventory = $stmt->fetch(PDO::FETCH_ASSOC);
    
            error_log("Product ID: $productId, Inventory Data: " . json_encode($inventory));
    
            if (!$inventory || $inventory['quantity'] < $quantityToBuy) {
                $this->pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Not enough stock or inventory not found']);
                return false;
            }
    
            $newInventoryQuantity = $inventory['quantity'] - $quantityToBuy;
            $updateInventoryQuery = "UPDATE inventory SET quantity = :quantity WHERE product_id = :product_id";
            $this->executeQuery($updateInventoryQuery, [
                ':quantity' => $newInventoryQuantity,
                ':product_id' => $productId
            ]);
    
            // Step 2: Update product quantity
            $updateProductQuery = "UPDATE products SET quantity = :quantity WHERE id = :id";
            $this->executeQuery($updateProductQuery, [
                ':quantity' => $newInventoryQuantity,
                ':id' => $productId
            ]);
    
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error updating inventory and product: " . $e->getMessage());
            return false;
        }
    }
    public function testConnection()
    {
        try {
            $stmt = $this->pdo->query("SELECT 1");
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            error_log("Connection failed: " . $e->getMessage());
            return false;
        }
    }
}
