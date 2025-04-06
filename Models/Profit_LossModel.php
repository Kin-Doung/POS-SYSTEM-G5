<?php
require_once './Models/DashboardModel.php';

class Profit_LossModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database();
    }

    // Fetch all profit/loss records
    function getProfit_Loss()
    {
        $stmt = $this->pdo->query("SELECT * FROM sales_data ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Create a new profit/loss record
    function createProfit_Loss($data)
    {
        $stmt = $this->pdo->query("INSERT INTO sales_data (image, Product_Name, quantity, Cost_Price, Selling_Price, Profit_Loss, Result_Type, Sale_Date, product_id, inventory_id) VALUES (:image, :Product_Name, :quantity, :Cost_Price, :Selling_Price, :Profit_Loss, :Result_Type, :Sale_Date, :product_id, :inventory_id)");
        $stmt->execute([
            'image' => $data['image'],
            'Product_Name' => $data['Product_Name'],
            'quantity' => $data['quantity'],
            'Cost_Price' => $data['Cost_Price'],
            'Selling_Price' => $data['Selling_Price'],
            'Profit_Loss' => $data['Profit_Loss'],
            'Result_Type' => $data['Result_Type'],
            'Sale_Date' => $data['Sale_Date'],
            'product_id' => $data['product_id'],
            'inventory_id' => $data['inventory_id'],
        ]);
    }

    // Get a single profit/loss record by ID
    function getProfit_Loss_By_Id($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM sales_data WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    function deleteProfit_Loss($id)
    {
        try {
            if (is_array($id)) {
                if (empty($id)) {
                    error_log("No IDs provided to delete");
                    return false;
                }
                // Convert all IDs to integers
                $id = array_map('intval', $id);
                error_log("Deleting multiple IDs: " . implode(',', $id));
                $placeholders = implode(',', array_fill(0, count($id), '?'));
                $sql = "DELETE FROM sales_data WHERE id IN ($placeholders)";
                $stmt = $this->pdo->query($sql);
                $stmt->execute($id);
            } else {
                $id = (int)$id; // Ensure single ID is integer
                error_log("Deleting single ID: " . $id);
                $sql = "DELETE FROM sales_data WHERE id = ?";
                $stmt = $this->pdo->query($sql);
                $stmt->execute([$id]);
            }
            $rowCount = $stmt->rowCount();
            error_log("Rows affected: " . $rowCount);
            return $rowCount > 0;
        } catch (PDOException $e) {
            error_log("Delete error: " . $e->getMessage());
            return false;
        }
    }

    // Add this temporary method to your model to test
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
}
