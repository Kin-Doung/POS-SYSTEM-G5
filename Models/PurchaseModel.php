<?php
require_once './Databases/database.php';

class PurchaseModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public function getCategories()
    {
        returndump($this->fetchAll("SELECT id, name FROM categories ORDER BY name ASC"));
    }

    public function getCategoryNameById($categoryId)
    {
        $category = $this->fetchOne("SELECT name FROM categories WHERE id = :category_id", ['category_id' => $categoryId]);
        return $category['name'] ?? null;
    }

    public function insertProduct($productName, $categoryId, $categoryName, $image = null, $barcode = null, $quantity = 1)
    {
        return $this->executeQuery(
            "INSERT INTO purchase (product_name, category_id, category_name, image, barcode, quantity) VALUES (:product_name, :category_id, :category_name, :image, :barcode, :quantity)",
            [
                ':product_name' => $productName,
                ':category_id' => $categoryId,
                ':category_name' => $categoryName,
                ':image' => $image,
                ':barcode' => $barcode,
                ':quantity' => $quantity
            ]
        );
    }

    public function insertProducts(array $products)
    {
        $sql = "INSERT INTO purchase (product_name, category_id, category_name, image, barcode, quantity) VALUES (:product_name, :category_id, :category_name, :image, :barcode, :quantity)";
        $stmt = $this->getConnection()->prepare($sql);

        foreach ($products as $product) {
            $this->bindAndExecute($stmt, $product);
        }

        return true;
    }

    public function getPurchases()
    {
        return $this->fetchAll("SELECT * FROM purchase ORDER BY id DESC");
    }

    public function getPurchase($id)
    {
        return $this->fetchOne("SELECT * FROM purchase WHERE id = :id", [':id' => $id]);
    }

    public function createPurchase($data)
    {
        return $this->executeQuery(
            "INSERT INTO purchase (product_name, category_id, quantity, image, barcode) VALUES (:product_name, :category_id, :quantity, :image, :barcode)",
            [
                ':product_name' => $data['product_name'],
                ':category_id' => $data['category_id'],
                ':quantity' => $data['quantity'] ?? 1,
                ':image' => $data['image'] ?? null,
                ':barcode' => $data['barcode'] ?? null
            ]
        );
    }

    public function updatePurchase($id, $data)
    {
        $sql = "UPDATE purchase SET product_name = :product_name, category_id = :category_id, barcode = :barcode, quantity = :quantity";
        if (isset($data['image']) && $data['image'] !== null) {
            $sql .= ", image = :image";
        }
        $sql .= " WHERE id = :id";
        $params = array_merge(
            [
                ':product_name' => $data['product_name'],
                ':category_id' => $data['category_id'],
                ':barcode' => $data['barcode'] ?? null,
                ':quantity' => $data['quantity'] ?? 1,
                ':id' => $id
            ],
            isset($data['image']) && $data['image'] !== null ? [':image' => $data['image']] : []
        );
        return $this->executeQuery($sql, $params);
    }

    public function deletePurchase($id)
    {
        return $this->executeQuery("DELETE FROM purchase WHERE id = :id", [':id' => $id]);
    }

    public function bulkDelete($ids)
    {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        return $this->executeQuery("DELETE FROM purchase WHERE id IN ($placeholders)", $ids);
    }

    private function fetchAll($sql, $params = [])
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error fetching data: ' . $e->getMessage());
        }
    }

    private function fetchOne($sql, $params = [])
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error fetching data: ' . $e->getMessage());
        }
    }

    private function executeQuery($sql, $params = [])
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            throw new Exception('Error executing query: ' . $e->getMessage());
        }
    }

    private function bindAndExecute($stmt, $product)
    {
        $stmt->bindParam(':product_name', $product['product_name']);
        $stmt->bindParam(':category_id', $product['category_id']);
        $stmt->bindParam(':category_name', $product['category_name']);
        $stmt->bindParam(':image', $product['image'], PDO::PARAM_STR);
        $stmt->bindParam(':barcode', $product['barcode']);
        $quantity = $product['quantity'] ?? 1;
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            throw new Exception('Error inserting product: ' . $product['product_name']);
        }
    }

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