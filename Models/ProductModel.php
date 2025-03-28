    <?php
    require_once 'Databases/database.php';

    class ProductModel
    {
        private $pdo;

        public function __construct()
        {
            $this->pdo = (new Database())->getConnection();
        }


        
        // Fetch all products
        public function getProducts()
        {
            return $this->fetchAll("SELECT * FROM products ORDER BY id DESC");
        }
        public function getProductWithCategory($id)
        {
            $query = "
            SELECT 
            p.*, 
            c.name AS category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = :id
            LIMIT 1
            ";
            return $this->fetchOne($query, ['id' => $id]);
        }

        // Fetch a product by ID
        public function getProductById($id)
        {
            $query = "SELECT * FROM products WHERE id = :id LIMIT 1";
            return $this->fetchOne($query, ['id' => $id]);
        }

        // Fetch all categories
        public function getCategories()
        {
            return $this->fetchAll("SELECT * FROM categories ORDER BY id DESC");
        }
        // Fetch inventory details with product data
        public function getInventoryWithProductDetails()
        {
            $query = "
                SELECT 
                    inventory.id AS inventory_id,
                    inventory.product_name AS inventory_product_name,
                    inventory.quantity,
                    inventory.amount,
                    inventory.total_price,
                    inventory.expiration_date,
                    inventory.image,
                    categories.name AS category_name,
                    products.name AS product_name
                FROM inventory
                LEFT JOIN categories ON inventory.category_id = categories.id
                LEFT JOIN products ON products.category_id = categories.id
                LIMIT 25;
            ";

            return $this->fetchAll($query);
        }

        // Create a new product
        public function createProduct($data)
        {
            // Fetch the category name using the category_id from categories table
            $categoryNameQuery = "SELECT name FROM categories WHERE id = :category_id LIMIT 1";
            $stmt = $this->pdo->prepare($categoryNameQuery);
            $stmt->execute(['category_id' => $data['category_id']]);
            $categoryNameResult = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($categoryNameResult) {
                $categoryName = $categoryNameResult['name'];
            } else {
                // If category not found, set a default or return error
                $categoryName = 'Unknown Category';
            }

            // Include category_name in the data array
            $data['category_name'] = $categoryName;

            // Insert product with category_name included
            $query = "INSERT INTO products (category_id, name, category_name, barcode, price, purchase_id, created_at, quantity, image) 
                      VALUES (:category_id, :name, :category_name, :barcode, :price, :purchase_id, NOW(), :quantity, :image)";

            return $this->executeQuery($query, $data);
        }


        // Check if a product exists by name and category
        public function getProductByNameAndCategory($productName, $categoryName)
        {
            $query = "
                SELECT * FROM products 
                WHERE name = :name AND category_id = 
                (SELECT id FROM categories WHERE name = :category_name) 
                LIMIT 1";

            return $this->fetchOne($query, [
                'name' => $productName,
                'category_name' => $categoryName
            ]);
        }

        public function updateProductFromInventory($id, $data)
        {
            // Fetch the category name using the category_id
            $categoryNameQuery = "SELECT name FROM categories WHERE id = :category_id LIMIT 1";
            $stmt = $this->pdo->prepare($categoryNameQuery);
            $stmt->execute(['category_id' => $data['category_id']]);
            $categoryNameResult = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($categoryNameResult) {
                $categoryName = $categoryNameResult['name'];
            } else {
                // If category not found, set a default or return error
                $categoryName = 'Unknown Category';
            }
        
            // Include category_name in the data array
            $data['category_name'] = $categoryName;
        
            // Update product with the category_name
            $query = "UPDATE products 
                      SET name = :name, price = :price, quantity = :quantity, image = :image, category_name = :category_name 
                      WHERE id = :id";
        
            return $this->executeQuery($query, array_merge($data, ['id' => $id]));
        }
        


        // Helper function to fetch all rows
        private function fetchAll($query)
        {
            $stmt = $this->pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Helper function to fetch a single row
        private function fetchOne($query, $params)
        {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        public function insertProductsFromInventory()
        {
            $query = "
                INSERT INTO products (name, category_id, category_name, price, quantity, image)
                SELECT 
                    i.product_name, 
                    c.id AS category_id, 
                    c.name AS category_name, 
                    i.amount AS price, 
                    i.quantity, 
                    i.image
                FROM inventory i
                JOIN categories c ON i.category_id = c.id;
            ";

            $stmt = $this->executeQuery($query, []);
            return $stmt ? $stmt->rowCount() : 0;
        }


        public function getInventoryWithCategory()
        {
            $query = "
        SELECT 
            inventory.id AS inventory_id,
            inventory.product_name AS inventory_product_name,
            inventory.quantity,
            inventory.amount,
            inventory.total_price,
            inventory.expiration_date,
            inventory.image,
            categories.name AS category_name
        FROM inventory
        LEFT JOIN categories ON inventory.category_id = categories.id
    ";

            return $this->fetchAll($query);
        }

        // Helper function to execute a query (insert, update, delete)
        private function executeQuery($query, $params)
        {
            try {
                $stmt = $this->pdo->prepare($query);
                $stmt->execute($params);
                return $stmt;
            } catch (Exception $e) {
                error_log("Error executing query: " . $e->getMessage());
                error_log("SQL Query: " . $query); // Log the query
                return null;
            }
        }
    }
