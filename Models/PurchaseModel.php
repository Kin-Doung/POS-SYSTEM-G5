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

    // Insert Product
    public function insertProduct($product_name, $category_id, $quantity, $amount, $type_of_product) {
        $stmt = $this->pdo->query("INSERT INTO purchase (product_name, category_id, quantity, price, type_of_product) 
            VALUES (:product_name, :category_id, :quantity, :amount, :type_of_product)");

        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':type_of_product', $type_of_product);

        return $stmt->execute();
    }
    
    // Create Purchase (with image)
    function createPurchase($data)
    {
        // Ensure image is provided or set to null if not
        $imagePath = isset($data['image']) ? $data['image'] : null;

        $stmt = $this->pdo->query("INSERT INTO purchase (image, product_name, category_name, category_id, price, purchase_date) 
                            VALUES (:image, :product_name, :category_name, :category_id, :price, :purchase_date)");

        $stmt->bindParam(':image', $imagePath);
        $stmt->bindParam(':product_name', htmlspecialchars($data['product_name']));
        $stmt->bindParam(':category_name', htmlspecialchars($data['category_name']));
        $stmt->bindParam(':category_id', intval($data['category_id']));
        $stmt->bindParam(':price', floatval($data['price']));
        $stmt->bindParam(':purchase_date', $data['purchase_date']);

        $stmt->execute();
    }

    // Get a single purchase
    function getPurchase($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM purchase WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Update Purchase
    function updatePurchase($id, $data)
    {
        // Ensure image is provided or keep the old image
        $imagePath = isset($data['image']) ? $data['image'] : $data['current_image'];  // use current image if not updated

        $stmt = $this->pdo->query("UPDATE purchase SET image = :image, product_name = :product_name, category_name = :category_name, 
                            category_id = :category_id, price = :price, purchase_date = :purchase_date WHERE id = :id");

        $stmt->bindParam(':image', $imagePath);
        $stmt->bindParam(':product_name', htmlspecialchars($data['product_name']));
        $stmt->bindParam(':category_name', htmlspecialchars($data['category_name']));
        $stmt->bindParam(':category_id', intval($data['category_id']));
        $stmt->bindParam(':price', floatval($data['price']));
        $stmt->bindParam(':purchase_date', $data['purchase_date']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }

    // Delete Purchase
    function deletePurchase($id)
    {
        // First, get the current image path to delete the file if it exists
        $purchase = $this->getPurchase($id);
        if ($purchase && $purchase['image'] && file_exists($purchase['image'])) {
            unlink($purchase['image']);  // Delete the image if it exists
        }

        // Then delete the purchase record from the database
        $stmt = $this->pdo->query("DELETE FROM purchase WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>
