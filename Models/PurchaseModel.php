<?php
require_once './Databases/database.php';

class PurchaseModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database();
    }
    public function getConnection()
    {
        return $this->pdo->getConnection(); // Assuming Database has getConnection()
    }

    // Get all categories
    function getCategories()
    {
        $stmt = $this->pdo->getConnection()->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get all purchases
    function getPurchases()
    {
        $stmt = $this->pdo->getConnection()->prepare("SELECT * FROM purchase ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Insert Product
    public function insertProduct($product_name, $category_id, $quantity, $amount, $type_of_product, $expiration_date, $imageData = null)
    {
        try {
            // Fetch category_name from categories table
            $stmt = $this->pdo->getConnection()->prepare("SELECT name FROM categories WHERE id = :id");
            $stmt->execute([':id' => $category_id]);
            $category_name = $stmt->fetchColumn() ?: '';

            $stmt = $this->pdo->getConnection()->prepare("
                INSERT INTO purchase (product_name, category_id, category_name, quantity, price, type_of_product, expiration_date, image) 
                VALUES (:product_name, :category_id, :category_name, :quantity, :price, :type_of_product, :expiration_date, :image)
            ");

            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_name', $category_name);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':price', $amount);
            $stmt->bindParam(':type_of_product', $type_of_product);
            $stmt->bindParam(':expiration_date', $expiration_date); // New line
            $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB);

            $stmt->execute();

            return $this->pdo->getConnection()->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error inserting product: " . $e->getMessage());
            throw new Exception("Error inserting product.");
        }
    }
    // Get a single purchase
    function getPurchase($id)
    {
        $stmt = $this->pdo->getConnection()->prepare("SELECT * FROM purchase WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Update deletePurchase to remove image handling since it's in DB now
    // Single delete
    function deletePurchase($id)
    {
        try {
            $stmt = $this->pdo->getConnection()->prepare("DELETE FROM purchase WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting purchase: " . $e->getMessage());
            throw new Exception("Error deleting purchase: " . $e->getMessage());
        }
    }

    public function createPurchase($data)
    {
        try {
            $imageData = null; // Default value for image

            // Check if an image is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageData = file_get_contents($_FILES['image']['tmp_name']); // Convert image to binary
            }

            // Insert into database
            $stmt = $this->pdo->getConnection()->prepare("
                INSERT INTO purchase (image, product_name, category_name, category_id, price, purchase_date, expiration_date) 
                VALUES (:image, :product_name, :category_name, :category_id, :price, :purchase_date, :expiration_date)
            ");

            $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB); // Store binary image
            $stmt->bindParam(':product_name', $data['product_name']);
            $stmt->bindParam(':category_name', $data['category_name']);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':purchase_date', $data['purchase_date']);
            $stmt->bindParam(':expiration_date', $data['expiration_date']); // New line

            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creating purchase: " . $e->getMessage());
            throw new Exception("Error creating purchase.");
        }
    }

    // Check if category exists by category ID
    public function categoryExists($categoryId)
    {
        try {
            $stmt = $this->pdo->getConnection()->prepare("SELECT COUNT(*) FROM categories WHERE id = :category_id");
            $stmt->execute([':category_id' => $categoryId]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking category existence: " . $e->getMessage());
            return false;
        }
    }
    



    // Bulk delete




    public function updatePurchase($id, $data)
    {
        try {
            $imageData = null;

            // Check if a new image is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageData = file_get_contents($_FILES['image']['tmp_name']); // Convert image to binary
            } else {
                // Keep the existing image if no new one is uploaded
                $existingPurchase = $this->getPurchase($id);
                $imageData = $existingPurchase['image'];
            }

            // Update database record
            $stmt = $this->pdo->getConnection()->prepare("
            UPDATE purchase 
            SET image = :image, product_name = :product_name, category_name = :category_name, 
                category_id = :category_id, price = :price, purchase_date = :purchase_date 
            WHERE id = :id
        ");

            $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(':product_name', $data['product_name']);
            $stmt->bindParam(':category_name', $data['category_name']);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':purchase_date', $data['purchase_date']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating purchase: " . $e->getMessage());
            throw new Exception("Error updating purchase.");
        }
    }

    // Add method for inline field update
    public function updateField($id, $field, $value)
    {
        try {
            $allowedFields = ['product_name', 'quantity', 'price', 'type_of_product'];
            if (!in_array($field, $allowedFields)) {
                throw new Exception("Invalid field: $field");
            }

            $stmt = $this->pdo->getConnection()->prepare(
                "UPDATE purchase SET $field = :value WHERE id = :id"
            );
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error updating field: " . $e->getMessage());
            throw new Exception("Error updating field: " . $e->getMessage());
        }
    }

    public function bulkDelete($ids)
    {
        try {
            if (empty($ids)) {
                throw new Exception("No IDs provided for deletion");
            }
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $this->pdo->getConnection()->prepare(
                "DELETE FROM purchase WHERE id IN ($placeholders)"
            );
            $stmt->execute($ids);
            return true;
        } catch (PDOException $e) {
            error_log("Error bulk deleting purchases: " . $e->getMessage());
            throw new Exception("Error bulk deleting purchases: " . $e->getMessage());
        }
    }

    // Transaction management
    public function startTransaction()
    {
        $this->pdo->getConnection()->beginTransaction();
    }
    public function commitTransaction()
    {
        $this->pdo->getConnection()->commit();
    }
    public function rollBackTransaction()
    {
        $this->pdo->getConnection()->rollBack();
    }
}
