<?php
require_once './Databases/database.php';

class InventoryModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database();
    }

    private function getConnection()
    {
        return $this->pdo->getConnection();
    }

    function getInventory()
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

    function getCategory()
    {
        $inventory = $this->pdo->query("SELECT * FROM categories ORDER BY id DESC");
        return $inventory->fetchAll();
    }

    function getInventoryWithCategory()
    {
        $query = "
        SELECT inventory.*, categories.name AS category_name
        FROM inventory
        LEFT JOIN categories ON inventory.category_id = categories.id
        ORDER BY inventory.id DESC
    ";
        $inventory = $this->pdo->query($query);
        return $inventory->fetchAll();
    }

    public function getInventoryById($id)
    {
        try {
            $stmt = $this->pdo->getConnection()->prepare("SELECT * FROM inventory WHERE id = :id");
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

    public function insertInventory($data)
    {
        $stmt = $this->pdo->getConnection()->prepare("
            INSERT INTO inventory (product_name, category_id, quantity, amount, category_name, expiration_date, total_price, image)
            VALUES (:product_name, :category_id, :quantity, :amount, :category_name, :expiration_date, :total_price, :image)
        ");
        $stmt->bindParam(':product_name', $data['product_name']);
        $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
        $stmt->bindParam(':amount', $data['amount']);
        $stmt->bindParam(':category_name', $data['category_name']);
        $stmt->bindParam(':expiration_date', $data['expiration_date']);
        $stmt->bindParam(':total_price', $data['total_price']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->execute();
    }

    public function createInventory(array $data): int
    {
        $defaults = [
            'image' => null,
            'product_name' => '',
            'category_id' => null,
            'quantity' => 0,
            'amount' => 0,
            'selling_price' => 0,
            'category_name' => '',
            'expiration_date' => null,
            'total_price' => 0
        ];
        $data = array_merge($defaults, $data);
    
        try {
            $stmt = $this->pdo->getConnection()->prepare("
                INSERT INTO inventory (image, product_name, category_id, quantity, amount, selling_price, category_name, expiration_date, total_price)
                VALUES (:image, :product_name, :category_id, :quantity, :amount, :selling_price, :category_name, :expiration_date, :total_price)
            ");
            $stmt->bindParam(':image', $data['image']);
            $stmt->bindParam(':product_name', $data['product_name']);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':amount', $data['amount']);
            $stmt->bindParam(':selling_price', $data['selling_price']);
            $stmt->bindParam(':category_name', $data['category_name']);
            $stmt->bindParam(':expiration_date', $data['expiration_date']);
            $stmt->bindParam(':total_price', $data['total_price']);
    
            $stmt->execute();
            return (int)$this->pdo->getConnection()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Insert error: " . $e->getMessage());
            throw new Exception("Failed to create inventory: " . $e->getMessage());
        }
    }

    public function createMultipleInventory($items)
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->query("INSERT INTO inventory (image, product_name, quantity, amount, category_id, category_name, expiration_date) 
                                         VALUES (:image, :product_name, :quantity, :amount, :category_id, :category_name, :expiration_date)");
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
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getProductByName($productName, $categoryId)
    {
        $stmt = $this->pdo->query("SELECT * FROM inventory WHERE product_name = :product_name AND category_id = :category_id");
        $stmt->execute([':product_name' => $productName, ':category_id' => $categoryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateInventoryQuantity($id, $quantity, $amount = null)
    {
        $sql = "UPDATE inventory SET quantity = :quantity";
        $params = [':quantity' => $quantity, ':id' => $id];
        if ($amount !== null) {
            $sql .= ", amount = :amount";
            $params[':amount'] = $amount;
        }
        $sql .= " WHERE id = :id";
        $stmt = $this->pdo->getConnection()->prepare($sql);
        $stmt->execute($params);
    }

    public function addNewProductToInventory($productName, $categoryId, $quantity, $amount, $type)
    {
        $stmt = $this->pdo->query("INSERT INTO inventory (product_name, category_id, quantity, amount, type) VALUES (:product_name, :category_id, :quantity, :amount, :type)");
        $stmt->execute([
            ':product_name' => $productName,
            ':category_id' => $categoryId,
            ':quantity' => $quantity,
            ':amount' => $amount,
            ':type' => $type
        ]);
    }

    private function executeQuery($query, $params)
    {
        try {
            $stmt = $this->pdo->query($query);
            $stmt->execute($params);
            return $stmt;
        } catch (Exception $e) {
            echo "Error executing query: " . $e->getMessage();
            echo "<br>Query: " . $query;
            return null;
        }
    }

    public function insertProductsFromInventory()
    {
        $query = "
        INSERT INTO products (name, category_id, price, quantity, image)
        SELECT 
            i.product_name, 
            c.id AS category_id, 
            i.amount AS price, 
            i.quantity, 
            i.image
        FROM inventory i
        JOIN categories c ON i.category_name = c.name;
    ";
        $stmt = $this->executeQuery($query, []);
        return $stmt ? $stmt->rowCount() : 0;
    }

    public function updateCategory($categoryId, $newCategoryName)
    {
        $sql = "UPDATE categories SET name = :new_category_name WHERE id = :category_id";
        $params = [
            ':new_category_name' => $newCategoryName,
            ':category_id' => $categoryId
        ];
        $this->pdo->query($sql, $params);
    }

    function getInventorys($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM inventory WHERE id = :id", ['id' => $id]);
        return $stmt->fetch();
    }

    function viewInventory($id)
    {
        $stmt = $this->pdo->query("
            SELECT inventory.*, categories.name
            FROM inventory 
            LEFT JOIN categories ON inventory.category_id = categories.id 
            WHERE inventory.id = :id
        ", ['id' => $id]);
        return $stmt->fetch();
    }

    public function updateInventory($id, $data)
    {
        try {
            $stmt = $this->getConnection()->prepare("
                UPDATE inventory 
                SET product_name = :product_name,
                    category_id = :category_id,
                    category_name = :category_name,
                    quantity = :quantity,
                    amount = :amount,
                    selling_price = :selling_price,
                    total_price = :total_price,
                    expiration_date = :expiration_date,
                    image = :image
                WHERE id = :id
            ");
            $stmt->execute([
                ':product_name' => $data['product_name'],
                ':category_id' => $data['category_id'],
                ':category_name' => $data['category_name'],
                ':quantity' => $data['quantity'],
                ':amount' => $data['amount'],
                ':selling_price' => $data['selling_price'],
                ':total_price' => $data['total_price'],
                ':expiration_date' => $data['expiration_date'],
                ':image' => $data['image'],
                ':id' => $id
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Update error: " . $e->getMessage());
            return false;
        }
    }

    public function getCategoryById($categoryId)
    {
        $stmt = $this->pdo->query("SELECT * FROM categories WHERE id = :category_id", ['category_id' => $categoryId]);
        return $stmt->fetch();
    }

    public function deleteItem($id)
    {
        try {
            if (is_numeric($id)) {
                $sql = "DELETE FROM inventory WHERE id = $id";
                $this->pdo->query($sql);
            } else {
                throw new Exception("Invalid ID");
            }
        } catch (Exception $e) {
            echo "Error deleting item: " . $e->getMessage();
        }
    }

    public function getInventoryItemByProduct($productName, $categoryId)
    {
        $stmt = $this->getConnection()->prepare("SELECT * FROM inventory WHERE product_name = :product_name AND category_id = :category_id");
        $stmt->execute([
            ':product_name' => $productName,
            ':category_id' => $categoryId
        ]);
        return $stmt->fetch();
    }

    public function addToInventory($data)
    {
        $sql = "INSERT INTO inventory (product_name, category_id, category_name, quantity, image, barcode, amount, selling_price, total_price, expiration_date) 
                VALUES (:product_name, :category_id, :category_name, :quantity, :image, :barcode, :amount, :selling_price, :total_price, :expiration_date)";
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $success = $stmt->execute([
                ':product_name' => $data['product_name'],
                ':category_id' => $data['category_id'],
                ':category_name' => $data['category_name'],
                ':quantity' => $data['quantity'],
                ':image' => $data['image'] ?? null,
                ':barcode' => $data['barcode'] ?? null,
                ':amount' => 0,
                ':selling_price' => 0,
                ':total_price' => 0,
                ':expiration_date' => null
            ]);
            error_log("addToInventory: product_name = {$data['product_name']}, barcode = " . ($data['barcode'] ?? 'null') . ", success = " . ($success ? 'yes' : 'no'));
            return $success;
        } catch (Exception $e) {
            error_log("addToInventory error: " . $e->getMessage());
            throw new Exception('Error adding to inventory: ' . $e->getMessage());
        }
    }

    public function getProductById($id)
    {
        $stmt = $this->pdo->query("SELECT p.*, c.name as category_name 
                                    FROM products p
                                    LEFT JOIN categories c ON p.category_id = c.id
                                    WHERE p.id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPurchaseByProductId($productId)
    {
        $stmt = $this->pdo->getConnection()->prepare("
            SELECT * FROM purchases WHERE product_id = :id
        ");
        $stmt->execute([':id' => $productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}