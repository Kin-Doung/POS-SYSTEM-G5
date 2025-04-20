<?php
require_once './Databases/database.php';

class Profit_LossModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database();
    }

    function getProfit_Loss($page = 1, $perPage = 25)
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("SELECT * FROM sales_data ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getTotalRecords()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM sales_data");
        return $stmt->fetchColumn();
    }

    function createProfit_Loss($data)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO sales_data (
                    image, Product_Name, quantity, Cost_Price, Selling_Price, 
                    Profit_Loss, Result_Type, Sale_Date, product_id, inventory_id
                ) VALUES (
                    :image, :Product_Name, :quantity, :Cost_Price, :Selling_Price, 
                    :Profit_Loss, :Result_Type, :Sale_Date, :product_id, :inventory_id
                )
            ");
            $stmt->execute([
                'image' => $data['image'] ?? null,
                'Product_Name' => $data['Product_Name'] ?? '',
                'quantity' => $data['quantity'] ?? 0,
                'Cost_Price' => $data['Cost_Price'] ?? 0,
                'Selling_Price' => $data['Selling_Price'] ?? 0,
                'Profit_Loss' => $data['Profit_Loss'] ?? 0,
                'Result_Type' => $data['Result_Type'] ?? '',
                'Sale_Date' => $data['Sale_Date'] ?? date('Y-m-d'),
                'product_id' => $data['product_id'] ?? 0,
                'inventory_id' => $data['inventory_id'] ?? 0
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Model: Create error: " . $e->getMessage());
            return false;
        }
    }

    function getProfit_Loss_By_Id($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM sales_data WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Model: Fetch by ID error: " . $e->getMessage());
            return false;
        }
    }

    function deleteProfit_Loss($id)
    {
        try {
            $this->pdo->beginTransaction();
            if (is_array($id)) {
                if (empty($id)) {
                    error_log("Model: No IDs provided to delete");
                    return false;
                }
                $id = array_filter($id, fn($val) => is_numeric($val) && $val > 0);
                if (empty($id)) {
                    error_log("Model: No valid IDs after filtering");
                    return false;
                }
                error_log("Model: Deleting multiple IDs: " . implode(',', $id));
                $placeholders = implode(',', array_fill(0, count($id), '?'));
                $sql = "DELETE FROM sales_data WHERE id IN ($placeholders)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($id);
            } else {
                if (!is_numeric($id) || $id <= 0) {
                    error_log("Model: Invalid ID: " . $id);
                    return false;
                }
                error_log("Model: Deleting single ID: " . $id);
                $sql = "DELETE FROM sales_data WHERE id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$id]);
            }
            $rowCount = $stmt->rowCount();
            $this->pdo->commit();
            error_log("Model: Rows deleted: " . $rowCount);
            return $rowCount > 0;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Model: Delete error: " . $e->getMessage());
            return false;
        }
    }

    function testConnection()
    {
        try {
            $stmt = $this->pdo->query("SELECT 1");
            return $stmt !== false;
        } catch (PDOException $e) {
            error_log("Connection test failed: " . $e->getMessage());
            return false;
        }
    }

    function getProfitLossTotals()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    SUM(CASE WHEN Result_Type = 'Profit' THEN Profit_Loss ELSE 0 END) as total_profit,
                    SUM(CASE WHEN Result_Type = 'Loss' THEN Profit_Loss ELSE 0 END) as total_loss
                FROM sales_data
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                'total_profit' => floatval($result['total_profit'] ?? 0),
                'total_loss' => floatval($result['total_loss'] ?? 0)
            ];
        } catch (PDOException $e) {
            error_log("Model: Totals error: " . $e->getMessage());
            return ['total_profit' => 0, 'total_loss' => 0];
        }
    }
}