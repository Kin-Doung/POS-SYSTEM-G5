<?php
require_once './Databases/database.php';

class DashboardModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database();
    }

    // Your existing methods (unchanged)
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
            return $result['total_value'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error fetching total inventory value: " . $e->getMessage());
            return 0;
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

    // New methods for reports table (added, not changing existing code)
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
}