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

    // In InventoryModel.php
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
        $stmt = $this->pdo->query("SELECT * FROM inventory WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertInventory($data)
    {
        $defaults = [
            'image' => null,
            'product_name' => '',
            'category_id' => null,
            'quantity' => 0,
            'amount' => 0,
            'category_name' => '',
            'expiration_date' => null,
            'total_price' => 0
        ];
        $data = array_merge($defaults, $data);

        $stmt = $this->pdo->getConnection()->prepare("
            INSERT INTO inventory (image, product_name, category_id, quantity, amount, category_name, expiration_date, total_price)
            VALUES (:image, :product_name, :category_id, :quantity, :amount, :category_name, :expiration_date, :total_price)
        ");
        $stmt->bindParam(':image', $data['image'], PDO::PARAM_LOB);
        $stmt->bindParam(':product_name', $data['product_name']);
        $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
        $stmt->bindParam(':amount', $data['amount']);
        $stmt->bindParam(':category_name', $data['category_name']);
        $stmt->bindParam(':expiration_date', $data['expiration_date']);
        $stmt->bindParam(':total_price', $data['total_price']);
        $stmt->execute();

        return $this->pdo->getConnection()->lastInsertId();
    }
    public function createInventory(array $data): int
    {
        $defaults = [
            'image' => null,
            'product_name' => '',
            'category_id' => null,
            'quantity' => 0,
            'amount' => 0,
            'category_name' => '',
            'expiration_date' => null,
            'total_price' => 0
        ];
        $data = array_merge($defaults, $data);

        try {
            $stmt = $this->pdo->getConnection()->prepare("
                INSERT INTO inventory (image, product_name, category_id, quantity, amount, category_name, expiration_date, total_price)
                VALUES (:image, :product_name, :category_id, :quantity, :amount, :category_name, :expiration_date, :total_price)
            ");
            $stmt->bindParam(':image', $data['image']); // String path
            $stmt->bindParam(':product_name', $data['product_name']);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':amount', $data['amount']);
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



    // Get product by name and category from the inventory table
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
    // Insert a new product into the inventory table if it's not already there
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
            echo "Error executing query: " . $e->getMessage(); // 👈 Add this
            echo "<br>Query: " . $query; // 👈 Add this
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
                // Use query() method directly without query
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
