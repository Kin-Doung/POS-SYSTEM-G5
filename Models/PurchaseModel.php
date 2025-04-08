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

    // Fetch category name by ID
    public function getCategoryNameById($categoryId)
    {
        $category = $this->fetchOne("SELECT name FROM categories WHERE id = :category_id", ['category_id' => $categoryId]);
        return $category['name'] ?? null;
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

    // Get a single purchase by ID
    public function getPurchase($id)
    {
        return $this->fetchOne("SELECT * FROM purchase WHERE id = :id", [':id' => $id]);
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
        $sql = "UPDATE purchase SET product_name = :product_name, category_id = :category_id";
        if (isset($data['image'])) {
            $sql .= ", image = :image";
        }
        $sql .= " WHERE id = :id";
        return $this->executeQuery($sql, array_merge($data, [':id' => $id]));
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


    // Helper method for fetching a single result
    private function fetchOne($sql, $params = [])
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error fetching data: ' . $e->getMessage());
        }
    }


    // Helper method to execute insert/update/delete queries
    private function executeQuery($sql, $params = [])
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            throw new Exception('Error executing query: ' . $e->getMessage());
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
