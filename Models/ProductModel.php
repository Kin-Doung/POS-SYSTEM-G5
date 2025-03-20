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
        $products = $this->pdo->query("SELECT * FROM products ORDER BY id DESC");
        return $products->fetchAll();
    }
    public function getPurchase()
    {
        $purchase = $this->pdo->query("SELECT * FROM purchase ORDER BY id DESC");
        return $purchase->fetchAll();
    }

    public function getCategory()
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function executeQuery($query, $params = [])
    {
        try {
            $stmt = $this->pdo->query($query);
            $stmt->execute($params);
            return $stmt;
        } catch (Exception $e) {
            error_log("Error executing query: " . $e->getMessage());
            return null;
        }
    }

    public function getPurchasesWithProductDetails()
    {
        $query = "
            SELECT 
                purchase.id AS id, 
                purchase.product_name AS product_name, 
                purchase.quantity AS product_quantity, 
                purchase.image AS product_image, 
                purchase.price AS product_price,  
                categories.name AS category_name, 
                purchase.purchase_date 
            FROM purchase
            LEFT JOIN categories ON purchase.category_id = categories.id
            ORDER BY purchase.purchase_date DESC
            LIMIT 25;
        ";
    
        $stmt = $this->executeQuery($query);
        if ($stmt) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log(print_r($result, true)); // Debugging statement
            return $result;
        } else {
            return [];
        }
    }
    

    public function createProduct($data)
    {
        $query = "INSERT INTO products (name, quantity, price, image) 
                  VALUES (:name, :quantity, :price, :image)";
        $this->executeQuery($query, [
            'name' => $data['name'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'image' => $data['image'],
        ]);
    }

    // public function getProduct($id)
    // {
    //     $query = "
    //         SELECT products.*, purchase.quantity AS purchase_quantity, purchase.purchase_date 
    //         FROM products 
    //         LEFT JOIN purchase ON products.id = purchase.product_id 
    //         WHERE products.id = :id
    //         ORDER BY products.id DESC
    //     ";
    //     $stmt = $this->executeQuery($query, ['id' => $id]);
    //     return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
    // }

    public function updateProduct($id, $data)
    {
        $query = "UPDATE products SET name = :name, image = :image, price = :price, quantity = :quantity WHERE id = :id";
        $this->executeQuery($query, [
            'name' => $data['name'],
            'image' => $data['image'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'id' => $id
        ]);
    }

    public function updateQuantity($id, $newQuantity)
    {
        $query = "UPDATE products SET quantity = :quantity WHERE id = :id";
        $this->executeQuery($query, [
            'quantity' => $newQuantity,
            'id' => $id
        ]);
    }

    public function updatePrice($id, $newPrice)
    {
        $query = "UPDATE products SET price = :price WHERE id = :id";
        $this->executeQuery($query, [
            'price' => $newPrice,
            'id' => $id
        ]);
    }
}
