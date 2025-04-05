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

    // Delete a profit/loss record by ID
    function deleteProfit_Loss($id)
    {
        $stmt = $this->pdo->query("DELETE FROM sales_data WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>
