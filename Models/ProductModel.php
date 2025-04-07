<?php
require_once 'Databases/database.php';

class ProductModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConnection();
    }

    public function getProducts()
    {
        return $this->fetchAll("SELECT * FROM products ORDER BY id DESC");
    }

    public function getCategories()
    {
        return $this->fetchAll("SELECT * FROM categories ORDER BY id DESC");
    }

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

    public function updateProductPrice($productId, $newPrice)
    {
        try {
            $this->pdo->beginTransaction();

            $query = "SELECT price, quantity FROM products WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product === false) {
                return false;
            }

            $oldPrice = $product['price'];
            $quantity = $product['quantity'];

            $historyQuery = "INSERT INTO price_history (product_id, old_price, new_price, changed_at) VALUES (:product_id, :old_price, :new_price, NOW())";
            $this->executeQuery($historyQuery, [
                ':product_id' => $productId,
                ':old_price' => $oldPrice,
                ':new_price' => $newPrice
            ]);

            $updateQuery = "UPDATE products SET price = :price WHERE id = :id";
            $this->executeQuery($updateQuery, [
                ':price' => $newPrice,
                ':id' => $productId
            ]);

            $inventoryUpdateQuery = "UPDATE inventory SET quantity = quantity - :quantity WHERE product_id = :product_id";
            $this->executeQuery($inventoryUpdateQuery, [
                ':quantity' => $quantity,
                ':product_id' => $productId
            ]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error updating product price: " . $e->getMessage());
            return false;
        }
    }

    public function createProduct($data)
    {
        $query = "INSERT INTO products (category_id, name, barcode, price, purchase_id, created_at, quantity, image) 
                  VALUES (:category_id, :name, :barcode, :price, :purchase_id, NOW(), :quantity, :image)";
        return $this->executeQuery($query, $data);
    }

    public function getProductByNameAndCategory($productName, $categoryName)
    {
        $query = "
            SELECT * FROM products 
            WHERE name = :name AND category_id = 
            (SELECT id FROM categories WHERE name = :category_name LIMIT 1) 
            LIMIT 1";
        return $this->fetchOne($query, ['name' => $productName, 'category_name' => $categoryName]);
    }

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

    public function getPriceHistory($productId)
    {
        return $this->fetchAll("SELECT * FROM price_history WHERE product_id = :product_id ORDER BY changed_at DESC", ['product_id' => $productId]);
    }

    public function deductInventoryAndUpdateProduct($productId, $quantityToBuy)
    {
        try {
            $this->pdo->beginTransaction();

            $productStmt = $this->pdo->prepare("SELECT quantity FROM products WHERE id = :id FOR UPDATE");
            $productStmt->execute([':id' => $productId]);
            $product = $productStmt->fetch(PDO::FETCH_ASSOC);

            $inventoryStmt = $this->pdo->prepare("SELECT quantity FROM inventory WHERE product_id = :product_id FOR UPDATE");
            $inventoryStmt->execute([':product_id' => $productId]);
            $inventory = $inventoryStmt->fetch(PDO::FETCH_ASSOC);

            if (
                !$product || !$inventory ||
                $product['quantity'] < $quantityToBuy ||
                $inventory['quantity'] < $quantityToBuy
            ) {
                $this->pdo->rollBack();
                return false;
            }

            $newQuantity = $product['quantity'] - $quantityToBuy;

            $this->pdo->prepare("UPDATE products SET quantity = :quantity WHERE id = :id")
                ->execute([':quantity' => $newQuantity, ':id' => $productId]);

            $this->pdo->prepare("UPDATE inventory SET quantity = :quantity WHERE product_id = :product_id")
                ->execute([':quantity' => $newQuantity, ':product_id' => $productId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error in deductInventoryAndUpdateProduct: " . $e->getMessage());
            return false;
        }
    }

    public function processCartSubmission($cartItems)
    {
        try {
            $this->pdo->beginTransaction();

            foreach ($cartItems as $item) {
                $productId = $item['productId'];
                $quantityToBuy = (int)$item['quantity'];

                // Lock and check product
                $productStmt = $this->pdo->prepare("SELECT quantity FROM products WHERE id = :id FOR UPDATE");
                $productStmt->execute([':id' => $productId]);
                $product = $productStmt->fetch(PDO::FETCH_ASSOC);

                if (!$product) {
                    throw new Exception("Product not found: ID $productId");
                }

                // Lock and check inventory
                $inventoryStmt = $this->pdo->prepare("SELECT quantity FROM inventory WHERE product_id = :product_id FOR UPDATE");
                $inventoryStmt->execute([':product_id' => $productId]);
                $inventory = $inventoryStmt->fetch(PDO::FETCH_ASSOC);

                if (!$inventory) {
                    throw new Exception("No inventory record for product ID: $productId");
                }

                // Verify sufficient stock
                if ($product['quantity'] < $quantityToBuy) {
                    throw new Exception("Insufficient product stock for ID: $productId. Available: {$product['quantity']}");
                }
                if ($inventory['quantity'] < $quantityToBuy) {
                    throw new Exception("Insufficient inventory stock for ID: $productId. Available: {$inventory['quantity']}");
                }

                // Calculate new quantities
                $newProductQty = $product['quantity'] - $quantityToBuy;
                $newInventoryQty = $inventory['quantity'] - $quantityToBuy;

                // Update products table
                $productUpdate = $this->pdo->prepare("UPDATE products SET quantity = :quantity WHERE id = :id");
                $productSuccess = $productUpdate->execute([
                    ':quantity' => $newProductQty,
                    ':id' => $productId
                ]);

                if (!$productSuccess) {
                    throw new Exception("Failed to update product quantity for ID: $productId");
                }

                // Update inventory table
                $inventoryUpdate = $this->pdo->prepare("UPDATE inventory SET quantity = :quantity WHERE product_id = :product_id");
                $inventorySuccess = $inventoryUpdate->execute([
                    ':quantity' => $newInventoryQty,
                    ':product_id' => $productId
                ]);

                if (!$inventorySuccess) {
                    throw new Exception("Failed to update inventory quantity for ID: $productId");
                }

                $this->logTransaction($productId, $quantityToBuy);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Cart processing failed: " . $e->getMessage());
            throw $e;
        }
    }
    private function logTransaction($productId, $quantity)
    {
        try {
            $query = "INSERT INTO transaction_log (product_id, quantity_sold, transaction_date) 
                     VALUES (:product_id, :quantity, NOW())";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':product_id' => $productId,
                ':quantity' => $quantity
            ]);
        } catch (Exception $e) {
            error_log("Error logging transaction: " . $e->getMessage());
        }
    }

    // ... (keeping all other helper methods unchanged) ...

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

    public function syncProductQuantityFromInventory($inventoryId, $quantity)
    {
        try {
            $this->pdo->beginTransaction();

            $inventoryStmt = $this->pdo->prepare("SELECT product_name, quantity, amount FROM inventory WHERE id = :id");
            $inventoryStmt->execute([':id' => $inventoryId]);
            $inventory = $inventoryStmt->fetch(PDO::FETCH_ASSOC);

            if (!$inventory) {
                throw new Exception("Inventory item not found: ID $inventoryId");
            }

            $productStmt = $this->pdo->prepare("SELECT id FROM products WHERE name = :name LIMIT 1");
            $productStmt->execute([':name' => $inventory['product_name']]);
            $product = $productStmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $updateStmt = $this->pdo->prepare("UPDATE products SET quantity = :quantity WHERE id = :id");
                $updateStmt->execute([
                    ':quantity' => $quantity,
                    ':id' => $product['id']
                ]);
            } else {
                $insertStmt = $this->pdo->prepare(
                    "INSERT INTO products (name, price, quantity, created_at) 
                     VALUES (:name, :price, :quantity, NOW())"
                );
                $insertStmt->execute([
                    ':name' => $inventory['product_name'],
                    ':price' => $inventory['amount'],
                    ':quantity' => $quantity
                ]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error syncing quantity: " . $e->getMessage());
            throw $e; // Propagate exception to controller
        }
    }



    public function saveToReports($productId, $productName, $quantity, $price, $totalPrice, $image)
    {
        try {
            $query = "INSERT INTO reports (product_id, product_name, quantity, price, total_price, image, created_at) 
                      VALUES (:product_id, :product_name, :quantity, :price, :total_price, :image, NOW())";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':product_id' => $productId,
                ':product_name' => $productName,
                ':quantity' => $quantity,
                ':price' => $price,
                ':total_price' => $totalPrice,
                ':image' => $image
            ]);
            return true;
        } catch (Exception $e) {
            error_log("Error saving to reports: " . $e->getMessage());
            return false;
        }
    }
    // In ProductModel.php
    public function processCartSubmissionWithInventory($cartItems)
    {
        try {
            $this->pdo->beginTransaction();

            foreach ($cartItems as $item) {
                $inventoryId = $item['inventoryId'];
                $quantityToBuy = (int)$item['quantity'];
                $cartPrice = isset($item['price']) ? (float)$item['price'] : null;

                // Lock and check inventory
                $inventoryStmt = $this->pdo->prepare("SELECT product_name, quantity, amount, image FROM inventory WHERE id = :id FOR UPDATE");
                $inventoryStmt->execute([':id' => $inventoryId]);
                $inventory = $inventoryStmt->fetch(PDO::FETCH_ASSOC);

                if (!$inventory) {
                    throw new Exception("Inventory not found: ID $inventoryId");
                }

                // Lock and check product
                $productStmt = $this->pdo->prepare("SELECT id, quantity FROM products WHERE name = :name FOR UPDATE");
                $productStmt->execute([':name' => $inventory['product_name']]);
                $product = $productStmt->fetch(PDO::FETCH_ASSOC);

                if (!$product) {
                    throw new Exception("Product not found for inventory ID: $inventoryId");
                }

                if ($inventory['quantity'] < $quantityToBuy || $product['quantity'] < $quantityToBuy) {
                    throw new Exception("Insufficient stock for ID: $inventoryId");
                }

                $newQty = $inventory['quantity'] - $quantityToBuy;
                $priceToUse = $cartPrice ?? $inventory['amount']; // Prefer cart price if provided
                $totalPrice = $quantityToBuy * $priceToUse;

                // Update inventory
                $inventoryUpdate = $this->pdo->prepare("UPDATE inventory SET quantity = :quantity WHERE id = :id");
                $inventoryUpdate->execute([':quantity' => $newQty, ':id' => $inventoryId]);

                // Update product
                $productUpdate = $this->pdo->prepare("UPDATE products SET quantity = :quantity WHERE id = :id");
                $productUpdate->execute([':quantity' => $newQty, ':id' => $product['id']]);

                // Save to reports
                $reportSuccess = $this->saveToReports(
                    $product['id'],              // product_id
                    $inventory['product_name'],  // product_name
                    $quantityToBuy,             // quantity
                    $priceToUse,                // price
                    $totalPrice,                // total_price
                    $inventory['image']         // image
                );

                if (!$reportSuccess) {
                    throw new Exception("Failed to save report for inventory ID: $inventoryId");
                }

                $this->logTransaction($product['id'], $quantityToBuy);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Cart processing failed: " . $e->getMessage());
            throw $e;
        }
    }
}