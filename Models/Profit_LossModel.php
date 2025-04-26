<?php
require_once './Databases/database.php';

class Profit_LossModel
{
    private $pdo;

    function __construct()
    {
        try {
            $this->pdo = new Database();
            if (!$this->pdo->getConnection()) {
                error_log("Model: Failed to get PDO connection");
                throw new Exception("Database connection unavailable");
            }
        } catch (Exception $e) {
            error_log("Model: Constructor error: " . $e->getMessage());
            throw $e;
        }
    }

    function getProfit_Loss($page = 1, $perPage = 25)
    {
        try {
            $offset = ($page - 1) * $perPage;
            $stmt = $this->pdo->prepare("SELECT * FROM sales_data ORDER BY ID DESC LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Model: Fetched " . count($results) . " profit/loss records");
            return $results;
        } catch (PDOException $e) {
            error_log("Model: Fetch profit/loss error: " . $e->getMessage());
            return [];
        }
    }

    function getTotalRecords()
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM sales_data");
            $count = $stmt->fetchColumn();
            error_log("Model: Total records: $count");
            return $count;
        } catch (PDOException $e) {
            error_log("Model: Count records error: " . $e->getMessage());
            return 0;
        }
    }

    function createProfit_Loss($data)
    {
        try {
            // Validation
            if (!in_array($data['Result_Type'] ?? '', ['', 'Profit', 'Loss'])) {
                error_log("Model: Invalid Result_Type: " . ($data['Result_Type'] ?? ''));
                return false;
            }
            if (!is_numeric($data['quantity']) || $data['quantity'] < 0) {
                error_log("Model: Invalid quantity: " . ($data['quantity'] ?? ''));
                return false;
            }
            if (!is_numeric($data['Cost_Price']) || !is_numeric($data['Selling_Price']) || !is_numeric($data['Profit_Loss'])) {
                error_log("Model: Invalid numeric fields: Cost_Price=" . ($data['Cost_Price'] ?? '') . ", Selling_Price=" . ($data['Selling_Price'] ?? '') . ", Profit_Loss=" . ($data['Profit_Loss'] ?? ''));
                return false;
            }
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['Sale_Date'] ?? date('Y-m-d'))) {
                error_log("Model: Invalid Sale_Date: " . ($data['Sale_Date'] ?? ''));
                return false;
            }
            $stmt = $this->pdo->prepare("
                INSERT INTO sales_data (
                    image, Product_Name, quantity, Cost_Price, Selling_Price, 
                    Profit_Loss, Result_Type, Sale_Date, product_id, report_id, inventory_id
                ) VALUES (
                    :image, :Product_Name, :quantity, :Cost_Price, :Selling_Price, 
                    :Profit_Loss, :Result_Type, :Sale_Date, :product_id, :report_id, :inventory_id
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
                'report_id' => $data['report_id'] ?? 0,
                'inventory_id' => $data['inventory_id'] ?? 0
            ]);
            error_log("Model: Created record, rows affected: " . $stmt->rowCount());
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Model: Create error: " . $e->getMessage());
            return false;
        }
    }

    function getProfit_Loss_By_Id($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                error_log("Model: Invalid ID for fetch: $id");
                return false;
            }
            $stmt = $this->pdo->prepare("SELECT * FROM sales_data WHERE ID = :id");
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Model: Fetched record for ID $id: " . ($result ? 'Found' : 'Not found'));
            return $result;
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
                    error_log("Model: No valid IDs after filtering: " . json_encode($id));
                    return false;
                }
                error_log("Model: Deleting multiple IDs: " . implode(',', $id));
                $placeholders = implode(',', array_fill(0, count($id), '?'));
                $checkStmt = $this->pdo->prepare("SELECT ID FROM sales_data WHERE ID IN ($placeholders)");
                $checkStmt->execute($id);
                $existingIds = $checkStmt->fetchAll(PDO::FETCH_COLUMN);
                error_log("Model: Existing IDs: " . implode(',', $existingIds));
                if (empty($existingIds)) {
                    error_log("Model: No matching records found for deletion");
                    $this->pdo->commit();
                    return false;
                }
                $sql = "DELETE FROM sales_data WHERE ID IN ($placeholders)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($id);
            } else {
                if (!is_numeric($id) || $id <= 0) {
                    error_log("Model: Invalid ID: " . $id);
                    return false;
                }
                error_log("Model: Deleting single ID: " . $id);
                $checkStmt = $this->pdo->prepare("SELECT ID FROM sales_data WHERE ID = ?");
                $checkStmt->execute([$id]);
                if (!$checkStmt->fetch()) {
                    error_log("Model: ID $id not found in sales_data");
                    $this->pdo->commit();
                    return false;
                }
                $sql = "DELETE FROM sales_data WHERE ID = ?";
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
            $result = $stmt !== false;
            error_log("Model: Connection test: " . ($result ? 'Success' : 'Failed'));
            return $result;
        } catch (PDOException $e) {
            error_log("Model: Connection test failed: " . $e->getMessage());
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
            $totals = [
                'total_profit' => floatval($result['total_profit'] ?? 0),
                'total_loss' => floatval($result['total_loss'] ?? 0)
            ];
            error_log("Model: Totals: " . json_encode($totals));
            return $totals;
        } catch (PDOException $e) {
            error_log("Model: Totals error: " . $e->getMessage());
            return ['total_profit' => 0, 'total_loss' => 0];
        }
    }
}