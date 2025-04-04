<?php
require_once './Databases/database.php';

class PurchaseModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }

    // Get database connection
    private function getConnection()
    {
        return $this->pdo->getConnection();
    }


    // Fetch all categories
    public function getCategories()
    {
        return $this->fetchAll("SELECT id, name FROM categories ORDER BY name ASC");
    }

    // Insert a product (single)
    public function insertProduct($productName, $categoryId, $categoryName, $image = null)
    {
        return $this->executeQuery(
            "INSERT INTO purchase (product_name, category_id, category_name, image) VALUES (:product_name, :category_id, :category_name, :image)",
            [
                ':product_name' => $productName,
                ':category_id' => $categoryId,
                ':category_name' => $categoryName,
                ':image' => $image
            ]
        );
    }

    // Insert multiple products
    public function insertProducts(array $products)
    {
        $sql = "INSERT INTO purchase (product_name, category_id, category_name, image) VALUES (:product_name, :category_id, :category_name, :image)";
        $stmt = $this->getConnection()->prepare($sql);

        foreach ($products as $product) {
            $this->bindAndExecute($stmt, $product);
        }

        return true;
    }

    // Get all purchases
    public function getPurchases()
    {
        return $this->fetchAll("SELECT * FROM purchase ORDER BY id DESC");
    }

    // Create a new purchase
    public function createPurchase($data)
    {
        return $this->executeQuery(
            "INSERT INTO purchase (product_name, category_id, purchase_price, image) VALUES (:product_name, :category_id, :purchase_price, :image)",
            [
                ':product_name' => $data['product_name'],
                ':category_id' => $data['category_id'],
                ':purchase_price' => $data['purchase_price'],
                ':image' => $data['image'] ?? null
            ]
        );
    }


    public function updatePurchase($id, $data)
    {
        $sql = "UPDATE purchase SET product_name = :product_name WHERE id = :id";
        $params = [
            ':product_name' => $data['product_name'],
            ':id' => (int)$id
        ];

        $result = $this->executeQuery($sql, $params);
        return $result; // Return true if query succeeds, false otherwise
    }

    private function executeQuery($sql, $params = [])
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $success = $stmt->execute($params);
            if (!$success) {
                error_log("Query failed: " . json_encode($stmt->errorInfo()));
            }
            return $success;
        } catch (Exception $e) {
            error_log("Query Exception: " . $e->getMessage());
            throw $e;
        }
    }



    // Delete a purchase by ID
    public function deletePurchase($id)
    {
        return $this->executeQuery("DELETE FROM purchase WHERE id = :id", [':id' => $id]);
    }

    // Bulk delete purchases
    public function bulkDelete($ids)
    {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        return $this->executeQuery("DELETE FROM purchase WHERE id IN ($placeholders)", $ids);
    }

    // Helper method for fetching all results
    private function fetchAll($sql, $params = [])
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error fetching data: ' . $e->getMessage());
        }
    }



    // Helper method to bind parameters and execute a prepared statement
    private function bindAndExecute($stmt, $product)
    {
        $stmt->bindParam(':product_name', $product['product_name']);
        $stmt->bindParam(':category_id', $product['category_id']);
        $stmt->bindParam(':category_name', $product['category_name']);
        $stmt->bindParam(':image', $product['image'], PDO::PARAM_LOB);  // Bind image as BLOB
        if (!$stmt->execute()) {
            throw new Exception('Error inserting product: ' . $product['product_name']);
        }
    }

    // Transaction management
    public function startTransaction()
    {
        $this->getConnection()->beginTransaction();
    }

    public function commitTransaction()
    {
        $this->getConnection()->commit();
    }

    public function rollBackTransaction()
    {
        $this->getConnection()->rollBack();
    }
}
?>
