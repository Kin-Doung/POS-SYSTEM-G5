<?php
require_once './Databases/database.php';

class PurchaseModel
{
    private $pdo;
    
    function __construct()
    {
        $this->pdo = new Database();
    }

    // Get all categories
    function getCategories()
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    function getPurchases()
    {
        $purchase = $this->pdo->query("SELECT * FROM purchase ORDER BY id DESC");
        return $purchase->fetchAll();
    }

    function createPurchase($data)
    {
        // Ensure image is provided or set to null if not
        $imagePath = isset($data['image']) ? $data['image'] : null;
        
        $this->pdo->query("INSERT INTO purchase (image, product_name, category_name, category_id, price, purchase_date) 
                            VALUES (:image, :product_name, :category_name, :category_id, :price, :purchase_date)", [
            'image' => $imagePath,
            'product_name' => htmlspecialchars($data['product_name']),
            'category_name' => htmlspecialchars($data['category_name']),
            'category_id' => intval($data['category_id']),
            'price' => floatval($data['price']),
            'purchase_date' => $data['purchase_date'],
        ]);
    }

    function getPurchase($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM purchase WHERE id = :id", ['id' => $id]);
        return $stmt->fetch();
    }

    function updatePurchase($id, $data)
    {
        // Ensure image is provided or keep the old image
        $imagePath = isset($data['image']) ? $data['image'] : $data['current_image'];  // use current image if not updated
        
        $this->pdo->query("UPDATE purchase SET image = :image, product_name = :product_name, category_name = :category_name, 
                            category_id = :category_id, price = :price, purchase_date = :purchase_date WHERE id = :id", [
            'image' => $imagePath,
            'product_name' => htmlspecialchars($data['product_name']),
            'category_name' => htmlspecialchars($data['category_name']),
            'category_id' => intval($data['category_id']),
            'price' => floatval($data['price']),
            'purchase_date' => $data['purchase_date'],
            'id' => $id
        ]);
    }

    function deletePurchase($id)
    {
        // First, get the current image path to delete the file if it exists
        $purchase = $this->getPurchase($id);
        if ($purchase && $purchase['image'] && file_exists($purchase['image'])) {
            unlink($purchase['image']);  // Delete the image if it exists
        }

        // Then delete the purchase record from the database
        $this->pdo->query("DELETE FROM purchase WHERE id = :id", ['id' => $id]);
    }
}
