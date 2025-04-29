<?php
require_once './Databases/database.php';

class InventoryModel
{
    private $pdo;

    function __construct()
    {
        error_log("InventoryModel initialized");
        $this->pdo = new Database();
    }

    private function getConnection()
    {
        return $this->pdo->getConnection();
    }

    public function getInventory()
    {
        try {
            $inventory = $this->pdo->query("SELECT * FROM inventory ORDER BY id DESC");
            $result = $inventory->fetchAll(PDO::FETCH_ASSOC);
            if (empty($result)) {
                error_log("No data found in inventory table.");
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Error fetching inventory: " . $e->getMessage());
            return [];
        }
    }

    public function getCategory()
    {
        try {
            $inventory = $this->pdo->query("SELECT * FROM categories ORDER BY id DESC");
            return $inventory->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching categories: " . $e->getMessage());
            return [];
        }
    }

    public function getInventoryWithCategory()
    {
        try {
            $query = "
                SELECT inventory.*, categories.name AS category_name
                FROM inventory
                LEFT JOIN categories ON inventory.category_id = categories.id
                ORDER BY inventory.id DESC
            ";
            $inventory = $this->pdo->query($query);
            return $inventory->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching inventory with category: " . $e->getMessage());
            return [];
        }
    }

    public function getInventoryById($id)
    {
        try {
            $stmt = $this->getConnection()->prepare("SELECT * FROM inventory WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                error_log("No product found for ID: $id");
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Error fetching product by ID $id: " . $e->getMessage());
            return false;
        }
    }

    public function getProductByBarcode($barcode)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT i.id, i.product_name, i.category_id, c.name as category_name, 
                       i.quantity, i.amount, i.selling_price, i.barcode, i.image
                FROM inventory i
                LEFT JOIN categories c ON i.category_id = c.id
                WHERE i.barcode = :barcode
                LIMIT 1
            ");
            $stmt->bindParam(':barcode', $barcode, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Error in getProductByBarcode: " . $e->getMessage());
            throw new Exception("Database error");
        }
    }

    public function store($data)
    {
        try {
            $stmt = $this->getConnection()->prepare("SELECT id, quantity FROM inventory WHERE product_name = :product_name");
            $stmt->execute([':product_name' => $data['product_name']]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existing) {
                $query = "
                    INSERT INTO inventory (
                        product_name, category_id, category_name, quantity, amount, 
                        selling_price, total_price, barcode, expiration_date, image
                    ) VALUES (
                        :product_name, :category_id, :category_name, :quantity, :amount,
                        :selling_price, :total_price, :barcode, :expiration_date, :image
                    )
                ";
                $stmt = $this->getConnection()->prepare($query);
                $totalPrice = ($data['quantity'] ?? 1) * ($data['amount'] ?? 0);
                $stmt->execute([
                    ':product_name' => $data['product_name'],
                    ':category_id' => $data['category_id'] ?? null,
                    ':category_name' => $data['category_name'] ?? null,
                    ':quantity' => $data['quantity'] ?? 0,
                    ':amount' => $data['amount'] ?? 0,
                    ':selling_price' => $data['selling_price'] ?? 0,
                    ':total_price' => $totalPrice,
                    ':barcode' => $data['barcode'] ?? null,
                    ':expiration_date' => $data['expiration_date'] ?? null,
                    ':image' => $data['image'] ?? null
                ]);
                $id = $this->getConnection()->lastInsertId();
                error_log("Inserted new product {$data['product_name']} with ID $id");
                return ['success' => true, 'id' => $id];
            }

            $newQuantity = $existing['quantity'] + ($data['quantity'] ?? 0);
            $newTotalPrice = $newQuantity * ($data['amount'] ?? 0);
            $stmt = $this->getConnection()->prepare("
                UPDATE inventory 
                SET quantity = :quantity,
                    amount = :amount,
                    selling_price = :selling_price,
                    total_price = :total_price,
                    category_name = :category_name,
                    category_id = :category_id,
                    barcode = :barcode,
                    expiration_date = :expiration_date,
                    image = :image
                WHERE id = :id
            ");
            $stmt->execute([
                ':quantity' => $newQuantity,
                ':amount' => $data['amount'] ?? 0,
                ':selling_price' => $data['selling_price'] ?? 0,
                ':total_price' => $newTotalPrice,
                ':category_name' => $data['category_name'] ?? null,
                ':category_id' => $data['category_id'] ?? null,
                ':barcode' => $data['barcode'] ?? null,
                ':expiration_date' => $data['expiration_date'] ?? null,
                ':image' => $data['image'] ?? null,
                ':id' => $existing['id']
            ]);
            error_log("Updated product {$data['product_name']} with ID {$existing['id']}, new quantity: $newQuantity");
            return ['success' => true, 'id' => $existing['id']];
        } catch (PDOException $e) {
            error_log("Error storing inventory: " . $e->getMessage());
            return ['error' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function updateInventory($id, $data) {
        try {
            $query = "UPDATE inventory SET 
                product_name = ?, category_id = ?, quantity = ?, amount = ?, 
                selling_price = ?, total_price = ?, expiration_date = ?, 
                image = ?, barcode = ?, category_name = ?
                WHERE id = ?";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                $data['product_name'], $data['category_id'], $data['quantity'], 
                $data['amount'], $data['selling_price'], $data['total_price'], 
                $data['expiration_date'], $data['image'], $data['barcode'], 
                $data['category_name'], $id
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Database error updating inventory id $id: " . $e->getMessage());
            return ['error' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function destroy($id)
    {
        try {
            $stmt = $this->getConnection()->prepare("DELETE FROM inventory WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $rowCount = $stmt->rowCount();
            if ($rowCount === 0) {
                error_log("No item deleted with ID $id.");
                return ['error' => 'Item not found'];
            }
            error_log("Deleted item with ID $id.");
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Error deleting item ID $id: " . $e->getMessage());
            return ['error' => 'Cannot delete item: It is referenced by other records'];
        }
    }

    public function viewInventory($id)
    {
        try {
            $stmt = $this->getConnection()->prepare("
                SELECT inventory.*, categories.name
                FROM inventory 
                LEFT JOIN categories ON inventory.category_id = categories.id 
                WHERE inventory.id = :id
            ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error viewing inventory id $id: " . $e->getMessage());
            return false;
        }
    }

    public function getCategoryById($categoryId)
    {
        try {
            $stmt = $this->getConnection()->prepare("SELECT * FROM categories WHERE id = :category_id");
            $stmt->execute(['category_id' => $categoryId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching category by id $categoryId: " . $e->getMessage());
            return false;
        }
    }

    public function getInventoryItemByProduct($productName, $categoryId)
    {
        try {
            $stmt = $this->getConnection()->prepare("
                SELECT * FROM inventory WHERE product_name = :product_name AND category_id = :category_id
            ");
            $stmt->execute([
                ':product_name' => $productName,
                ':category_id' => $categoryId
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching inventory item by product: " . $e->getMessage());
            return false;
        }
    }

    public function getProductById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT i.id, i.product_name, i.category_id, c.name as category_name, 
                       i.quantity, i.amount, i.selling_price, i.barcode, i.image
                FROM inventory i
                LEFT JOIN categories c ON i.category_id = c.id
                WHERE i.id = :id
                LIMIT 1
            ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Error in getProductById: " . $e->getMessage());
            throw new Exception("Database error");
        }
    }

    public function addToInventory($data)
    {
        try {
            $query = "INSERT INTO inventory (product_name, category_id, category_name, quantity, image, barcode) 
                      VALUES (:product_name, :category_id, :category_name, :quantity, :image, :barcode)";
            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute([
                'product_name' => $data['product_name'],
                'category_id' => $data['category_id'],
                'category_name' => $data['category_name'],
                'quantity' => $data['quantity'],
                'image' => $data['image'],
                'barcode' => $data['barcode']
            ]);
            if ($result) {
                error_log("Added new item to inventory: {$data['product_name']}");
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Error adding to inventory: " . $e->getMessage());
            return false;
        }
    }

    public function getPurchaseByProductId($productId)
    {
        try {
            $stmt = $this->getConnection()->prepare("
                SELECT * FROM purchases WHERE product_id = :id
            ");
            $stmt->execute([':id' => $productId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching purchase by product id $productId: " . $e->getMessage());
            return false;
        }
    }
}
