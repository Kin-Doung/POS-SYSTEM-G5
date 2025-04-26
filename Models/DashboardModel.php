<?php
// models/DashboardModel.php
require_once './Databases/database.php';

class DashboardModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database();
    }

    /**
     * Fetch stock status counts and percentages (Low, Medium, High) for all or filtered inventory
     * @param string|null $categoryId Optional category ID to filter inventory
     * @return array Stock status data
     */
    function getStockStatus($categoryId = null)
    {
        try {
            $query = "SELECT id, product_name, quantity, category_id FROM inventory";
            if ($categoryId !== null && $categoryId !== '') {
                $query .= " WHERE category_id = :category_id";
            }
            $query .= " ORDER BY id DESC";

            $stmt = $this->pdo->getConnection()->prepare($query);
            if ($categoryId !== null && $categoryId !== '') {
                $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            }
            $stmt->execute();
            $tracking = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Initialize counters
            $lowCount = 0;
            $mediumCount = 0;
            $highCount = 0;
            $totalFiltered = 0;

            // Calculate stock status counts
            foreach ($tracking as $item) {
                $quantity = $item['quantity'];
                if ($quantity < 0) continue;
                $totalFiltered++;
                if ($quantity >= 50) {
                    $highCount++;
                } elseif ($quantity >= 10) {
                    $mediumCount++;
                } else {
                    $lowCount++;
                }
            }

            // Calculate percentages
            $lowPercent = $totalFiltered > 0 ? ($lowCount / $totalFiltered) * 100 : 0;
            $mediumPercent = $totalFiltered > 0 ? ($mediumCount / $totalFiltered) * 100 : 0;
            $highPercent = $totalFiltered > 0 ? ($highCount / $totalFiltered) * 100 : 0;

            return [
                'lowCount' => $lowCount,
                'mediumCount' => $mediumCount,
                'highCount' => $highCount,
                'totalFiltered' => $totalFiltered,
                'lowPercent' => round($lowPercent),
                'mediumPercent' => round($mediumPercent),
                'highPercent' => round($highPercent)
            ];
        } catch (PDOException $e) {
            error_log("Error fetching stock status: " . $e->getMessage());
            return [
                'lowCount' => 0,
                'mediumCount' => 0,
                'highCount' => 0,
                'totalFiltered' => 0,
                'lowPercent' => 0,
                'mediumPercent' => 0,
                'highPercent' => 0,
                'error' => 'Failed to fetch stock status'
            ];
        }
    }

    function getProfit_Loss()
    {
        $stmt = $this->pdo->query("SELECT * FROM sales_data ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Fetched " . count($result) . " records");
        return $result;
    }

    function getTotalProfit()
    {
        $stmt = $this->pdo->query("SELECT SUM(Profit_Loss) as total_profit FROM sales_data");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_profit'] ?? 0;
    }

    function getNewClientSales()
    {
        $stmt = $this->pdo->query("SELECT SUM(Profit_Loss) as new_client_sales FROM sales_data WHERE Client_Type = 'New'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['new_client_sales'] ?? 0;
    }

    function getTotalSales()
    {
        $stmt = $this->pdo->query("SELECT SUM(Profit_Loss) as total_sales FROM sales_data");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_sales'] ?? 0;
    }

    function getInventoryItems()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM inventory");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Fetched " . count($result) . " inventory items");
            return $result;
        } catch (PDOException $e) {
            error_log("Error fetching inventory: " . $e->getMessage());
            return [];
        }
    }

    function getTotalInventoryValue()
    {
        try {
            $stmt = $this->pdo->query("SELECT SUM(quantity * amount) as total_value FROM inventory");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $currentValue = $result['total_value'] ?? 0;
            $this->updateMaxExpense($currentValue);
            return $currentValue;
        } catch (PDOException $e) {
            error_log("Error fetching total inventory value: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get the non-decreasing expense value
     * @return float
     */
    function getMaxExpense()
    {
        try {
            $stmt = $this->pdo->query("SELECT MAX(expense_value) as max_expense FROM expense_history");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['max_expense'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error fetching max expense: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Update the max expense if the current inventory value is higher
     * @param float $currentValue
     */
    private function updateMaxExpense($currentValue)
    {
        try {
            $currentMax = $this->getMaxExpense();
            if ($currentValue > $currentMax) {
                $stmt = $this->pdo->getConnection()->prepare("
                    INSERT INTO expense_history (expense_value, updated_at)
                    VALUES (:expense_value, NOW())
                ");
                $stmt->execute([':expense_value' => $currentValue]);
            }
        } catch (PDOException $e) {
            error_log("Error updating max expense: " . $e->getMessage());
        }
    }

    function getLowStockItems($threshold = 10)
    {
        try {
            $stmt = $this->pdo->query("SELECT product_name, quantity FROM inventory WHERE quantity <= ? ORDER BY quantity ASC");
            $stmt->execute([$threshold]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching low stock items: " . $e->getMessage());
            return [];
        }
    }

    function getInventoryCount()
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) as total_items FROM inventory");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_items'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error fetching inventory count: " . $e->getMessage());
            return 0;
        }
    }

    function getAllReports()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM reports ORDER BY created_at DESC");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Fetched " . count($result) . " reports");
            return $result;
        } catch (PDOException $e) {
            error_log("Error fetching reports: " . $e->getMessage());
            return [];
        }
    }

    function getTotalReportsSales()
    {
        try {
            $stmt = $this->pdo->query("SELECT SUM(total_price) as total_sales FROM reports");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_sales'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error fetching total sales from reports: " . $e->getMessage());
            return 0;
        }
    }

    function getReportsByDate($date)
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM reports WHERE DATE(created_at) = ? ORDER BY created_at DESC");
            $stmt->execute([$date]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Fetched " . count($result) . " reports for date: $date");
            return $result;
        } catch (PDOException $e) {
            error_log("Error fetching reports by date: " . $e->getMessage());
            return [];
        }
    }

    function getProductSalesSummary()
    {
        try {
            $stmt = $this->pdo->query("SELECT product_id, product_name, SUM(quantity) as total_quantity, SUM(total_price) as total_revenue 
                                       FROM reports 
                                       GROUP BY product_id, product_name 
                                       ORDER BY total_revenue DESC");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Fetched product sales summary with " . count($result) . " products");
            return $result;
        } catch (PDOException $e) {
            error_log("Error fetching product sales summary: " . $e->getMessage());
            return [];
        }
    }

    function getLowQuantityReports($threshold = 5)
    {
        try {
            $stmt = $this->pdo->query("SELECT product_name, quantity, created_at 
                                       FROM reports 
                                       WHERE quantity <= ? 
                                       ORDER BY quantity ASC");
            $stmt->execute([$threshold]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Fetched " . count($result) . " low quantity reports");
            return $result;
        } catch (PDOException $e) {
            error_log("Error fetching low quantity reports: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Add an item to the cart (stored in session)
     * @param int $productId
     * @param string $productName
     * @param float $unitPrice
     * @param int $quantity
     */
    public function addToCart($productId, $productName, $unitPrice, $quantity)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            $newTotal = $cart[$productId]['quantity'] * $unitPrice;
            // Only update total if it increases
            if ($newTotal > $cart[$productId]['total_price']) {
                $cart[$productId]['total_price'] = $newTotal;
            }
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'product_name' => $productName,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'total_price' => $quantity * $unitPrice
            ];
        }
        $_SESSION['cart'] = $cart;
    }

    /**
     * Update cart item quantity
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function updateCart($productId, $quantity)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'][$productId]) || $quantity < 0) {
            return false;
        }

        $cart = $_SESSION['cart'];
        $unitPrice = $cart[$productId]['unit_price'];
        $currentTotal = $cart[$productId]['total_price'];
        $newTotal = $quantity * $unitPrice;

        $cart[$productId]['quantity'] = $quantity;
        // Only update total_price if the new total is higher
        if ($newTotal > $currentTotal) {
            $cart[$productId]['total_price'] = $newTotal;
        }
        // If quantity is 0, remove item
        if ($quantity == 0) {
            unset($cart[$productId]);
        }
        $_SESSION['cart'] = $cart;
        return true;
    }

    /**
     * Get cart contents
     * @return array
     */
    public function getCart()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    /**
     * Calculate total cart price (sum of total_price fields)
     * @return float
     */
    public function getCartTotal()
    {
        $cart = $this->getCart();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['total_price'];
        }
        return $total;
    }

    /**
     * Process order and save to reports table
     * @return bool
     */
    public function processOrder()
    {
        try {
            $cart = $this->getCart();
            if (empty($cart)) {
                return false;
            }

            $conn = $this->pdo->getConnection();
            $conn->beginTransaction();

            foreach ($cart as $item) {
                // Insert into reports table
                $stmt = $conn->prepare("
                    INSERT INTO reports (product_id, product_name, quantity, total_price, created_at)
                    VALUES (:product_id, :product_name, :quantity, :total_price, NOW())
                ");
                $stmt->execute([
                    ':product_id' => $item['product_id'],
                    ':product_name' => $item['product_name'],
                    ':quantity' => $item['quantity'],
                    ':total_price' => $item['total_price']
                ]);

                // Update inventory
                $stmt = $conn->prepare("
                    UPDATE inventory
                    SET quantity = quantity - :quantity
                    WHERE id = :product_id
                ");
                $stmt->execute([
                    ':quantity' => $item['quantity'],
                    ':product_id' => $item['product_id']
                ]);
            }

            $conn->commit();
            // Clear cart after successful order
            $this->clearCart();
            return true;
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error processing order: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Clear the cart
     */
    public function clearCart()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['cart'] = [];
    }

    /**
     * Get product details by ID
     * @param int $productId
     * @return array|null
     */
    public function getProductById($productId)
    {
        try {
            $stmt = $this->pdo->getConnection()->prepare("
                SELECT id, product_name, amount AS unit_price, quantity
                FROM inventory
                WHERE id = :id
            ");
            $stmt->execute([':id' => $productId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Error fetching product: " . $e->getMessage());
            return null;
        }
    }
}