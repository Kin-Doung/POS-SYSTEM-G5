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

    public function getInventoryWithProductDetails($page = 1, $perPage = 5)
    {
        $offset = ($page - 1) * $perPage;
        $query = "
            SELECT 
                i.id AS inventory_id,
                i.product_name AS inventory_product_name,
                i.quantity,
                i.amount,
                i.selling_price,
                i.total_price,
                i.expiration_date,
                i.image,
                i.barcode,
                c.name AS category_name
            FROM inventory i
            LEFT JOIN categories c ON i.category_id = c.id
            ORDER BY i.id DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInventoryCount()
    {
        $query = "SELECT COUNT(*) FROM inventory";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
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

                $productStmt = $this->pdo->prepare("SELECT quantity FROM products WHERE id = :id FOR UPDATE");
                $productStmt->execute([':id' => $productId]);
                $product = $productStmt->fetch(PDO::FETCH_ASSOC);

                if (!$product) {
                    throw new Exception("Product not found: ID $productId");
                }

                $inventoryStmt = $this->pdo->prepare("SELECT quantity FROM inventory WHERE product_id = :product_id FOR UPDATE");
                $inventoryStmt->execute([':product_id' => $productId]);
                $inventory = $inventoryStmt->fetch(PDO::FETCH_ASSOC);

                if (!$inventory) {
                    throw new Exception("No inventory record for product ID: $productId");
                }

                if ($product['quantity'] < $quantityToBuy) {
                    throw new Exception("Insufficient product stock for ID: $productId. Available: {$product['quantity']}");
                }
                if ($inventory['quantity'] < $quantityToBuy) {
                    throw new Exception("Insufficient inventory stock for ID: $productId. Available: {$inventory['quantity']}");
                }

                $newProductQty = $product['quantity'] - $quantityToBuy;
                $newInventoryQty = $inventory['quantity'] - $quantityToBuy;

                $productUpdate = $this->pdo->prepare("UPDATE products SET quantity = :quantity WHERE id = :id");
                $productSuccess = $productUpdate->execute([
                    ':quantity' => $newProductQty,
                    ':id' => $productId
                ]);

                if (!$productSuccess) {
                    throw new Exception("Failed to update product quantity for ID: $productId");
                }

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

            $inventoryStmt = $this->pdo->prepare("SELECT product_name, quantity, selling_price FROM inventory WHERE id = :id");
            $inventoryStmt->execute([':id' => $inventoryId]);
            $inventory = $inventoryStmt->fetch(PDO::FETCH_ASSOC);

            if (!$inventory) {
                throw new Exception("Inventory item not found: ID $inventoryId");
            }

            $productStmt = $this->pdo->prepare("SELECT id FROM products WHERE name = :name LIMIT 1");
            $productStmt->execute([':name' => $inventory['product_name']]);
            $product = $productStmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $updateStmt = $this->pdo->prepare("UPDATE products SET quantity = :quantity, price = :price WHERE id = :id");
                $updateStmt->execute([
                    ':quantity' => $quantity,
                    ':price' => $inventory['selling_price'],
                    ':id' => $product['id']
                ]);
            } else {
                $insertStmt = $this->pdo->prepare(
                    "INSERT INTO products (name, price, quantity, created_at) 
                     VALUES (:name, :price, :quantity, NOW())"
                );
                $insertStmt->execute([
                    ':name' => $inventory['product_name'],
                    ':price' => $inventory['selling_price'],
                    ':quantity' => $quantity
                ]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error syncing quantity: " . $e->getMessage());
            throw $e;
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

    public function processCartSubmissionWithInventory($cartItems)
    {
        try {
            $this->pdo->beginTransaction();

            foreach ($cartItems as $item) {
                $inventoryId = $item['inventoryId'];
                $quantityToBuy = (int)$item['quantity'];
                $cartPrice = isset($item['price']) ? (float)$item['price'] : null;

                $inventoryStmt = $this->pdo->prepare("SELECT product_name, quantity, selling_price, image FROM inventory WHERE id = :id FOR UPDATE");
                $inventoryStmt->execute([':id' => $inventoryId]);
                $inventory = $inventoryStmt->fetch(PDO::FETCH_ASSOC);

                if (!$inventory) {
                    throw new Exception("Inventory not found: ID $inventoryId");
                }

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
                $priceToUse = $cartPrice ?? $inventory['selling_price'];
                $totalPrice = $quantityToBuy * $priceToUse;

                $inventoryUpdate = $this->pdo->prepare("UPDATE inventory SET quantity = :quantity WHERE id = :id");
                $inventoryUpdate->execute([':quantity' => $newQty, ':id' => $inventoryId]);

                $productUpdate = $this->pdo->prepare("UPDATE products SET quantity = :quantity WHERE id = :id");
                $productUpdate->execute([':quantity' => $newQty, ':id' => $product['id']]);

                $reportSuccess = $this->saveToReports(
                    $product['id'],
                    $inventory['product_name'],
                    $quantityToBuy,
                    $priceToUse,
                    $totalPrice,
                    $inventory['image']
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

    public function processCartSubmissionWithSalesData($cartItems)
    {
        try {
            $this->pdo->beginTransaction();

            foreach ($cartItems as $item) {
                $inventoryId = $item['inventoryId'];
                $quantityToBuy = (int)$item['quantity'];
                $cartSellingPrice = (float)$item['price'];

                $inventoryStmt = $this->pdo->prepare("
                    SELECT product_name, quantity, amount, selling_price, image 
                    FROM inventory 
                    WHERE id = :id 
                    FOR UPDATE
                ");
                $inventoryStmt->execute([':id' => $inventoryId]);
                $inventory = $inventoryStmt->fetch(PDO::FETCH_ASSOC);

                if (!$inventory) {
                    throw new Exception("Inventory not found: ID $inventoryId");
                }

                if (abs($cartSellingPrice - $inventory['selling_price']) > 0.01) {
                    throw new Exception("Invalid selling price for ID: $inventoryId");
                }

                $productStmt = $this->pdo->prepare("
                    SELECT id, quantity 
                    FROM products 
                    WHERE name = :name 
                    FOR UPDATE
                ");
                $productStmt->execute([':name' => $inventory['product_name']]);
                $product = $productStmt->fetch(PDO::FETCH_ASSOC);

                if (!$product) {
                    throw new Exception("Product not found for inventory ID: $inventoryId");
                }

                if ($inventory['quantity'] < $quantityToBuy || $product['quantity'] < $quantityToBuy) {
                    throw new Exception("Insufficient stock for ID: $inventoryId");
                }

                $newQty = $inventory['quantity'] - $quantityToBuy;
                $costPrice = (float)$inventory['amount'];
                $totalSellingPrice = $quantityToBuy * $cartSellingPrice;
                $resultType = ($quantityToBuy * ($cartSellingPrice - $costPrice)) > 0 ? 'Profit' : (($quantityToBuy * ($cartSellingPrice - $costPrice)) < 0 ? 'Loss' : 'Break-even');

                $inventoryUpdate = $this->pdo->prepare("
                    UPDATE inventory 
                    SET quantity = :quantity 
                    WHERE id = :id
                ");
                $inventoryUpdate->execute([':quantity' => $newQty, ':id' => $inventoryId]);

                $productUpdate = $this->pdo->prepare("
                    UPDATE products 
                    SET quantity = :quantity 
                    WHERE id = :id
                ");
                $productUpdate->execute([':quantity' => $newQty, ':id' => $product['id']]);

                $salesStmt = $this->pdo->prepare("
                    INSERT INTO sales_data (
                        Product_Name, 
                        Cost_Price, 
                        Selling_Price, 
                        Profit_Loss, 
                        Result_Type, 
                        Sale_Date, 
                        product_id, 
                        inventory_id, 
                        image, 
                        quantity
                    ) VALUES (
                        :product_name, 
                        :cost_price, 
                        :selling_price, 
                        :quantity * (:selling_price - :cost_price), 
                        :result_type, 
                        NOW(), 
                        :product_id, 
                        :inventory_id, 
                        :image, 
                        :quantity
                    )
                ");
                $salesStmt->execute([
                    ':product_name' => $inventory['product_name'],
                    ':cost_price' => $costPrice,
                    ':selling_price' => $cartSellingPrice,
                    ':quantity' => $quantityToBuy,
                    ':result_type' => $resultType,
                    ':product_id' => $product['id'],
                    ':inventory_id' => $inventoryId,
                    ':image' => $inventory['image']
                ]);

                $this->logTransaction($product['id'], $quantityToBuy);

                $this->saveToReports(
                    $product['id'],
                    $inventory['product_name'],
                    $quantityToBuy,
                    $cartSellingPrice,
                    $totalSellingPrice,
                    $inventory['image']
                );
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Cart processing with sales data failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteInventory($inventoryId)
    {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("SELECT product_name FROM inventory WHERE id = :id");
            $stmt->execute([':id' => $inventoryId]);
            $inventory = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$inventory) {
                throw new Exception("Inventory item not found: ID $inventoryId");
            }

            $deleteStmt = $this->pdo->prepare("DELETE FROM inventory WHERE id = :id");
            $success = $deleteStmt->execute([':id' => $inventoryId]);

            if (!$success) {
                throw new Exception("Failed to delete inventory item: ID $inventoryId");
            }

            $productStmt = $this->pdo->prepare("SELECT id FROM products WHERE name = :name LIMIT 1");
            $productStmt->execute([':name' => $inventory['product_name']]);
            $product = $productStmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $updateStmt = $this->pdo->prepare("UPDATE products SET quantity = 0 WHERE id = :id");
                $updateStmt->execute([':id' => $product['id']]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error deleting inventory: " . $e->getMessage());
            throw $e;
        }
    }

    public function getInventoryByBarcode($barcode)
    {
        $query = "
            SELECT 
                id AS inventory_id,
                product_name AS inventory_product_name,
                quantity,
                amount,
                selling_price,
                total_price,
                expiration_date,
                image,
                barcode
            FROM inventory 
            WHERE barcode = :barcode 
            LIMIT 1
        ";
        return $this->fetchOne($query, ['barcode' => $barcode]);
    }

    public function getProductPageByBarcode($barcode, $perPage = 4)
    {
        // Get the position of the item in the ordered list
        $query = "
            SELECT 
                (SELECT COUNT(*) + 1 
                 FROM inventory i2 
                 WHERE i2.id > i1.id 
                 ORDER BY i2.id DESC) AS position,
                i1.id AS inventory_id,
                i1.product_name AS inventory_product_name,
                i1.quantity,
                i1.amount,
                i1.selling_price,
                i1.total_price,
                i1.expiration_date,
                i1.image,
                i1.barcode
            FROM inventory i1
            WHERE i1.barcode = :barcode
            LIMIT 1
        ";
        $item = $this->fetchOne($query, ['barcode' => $barcode]);

        if (!$item) {
            return null;
        }

        // Calculate the page
        $position = $item['position'];
        $page = ceil($position / $perPage);

        return [
            'page' => $page,
            'item' => [
                'inventory_id' => $item['inventory_id'],
                'inventory_product_name' => $item['inventory_product_name'],
                'quantity' => $item['quantity'],
                'amount' => $item['amount'],
                'selling_price' => $item['selling_price'],
                'total_price' => $item['total_price'],
                'expiration_date' => $item['expiration_date'],
                'image' => $item['image'],
                'barcode' => $item['barcode']
            ]
        ];
    }
}