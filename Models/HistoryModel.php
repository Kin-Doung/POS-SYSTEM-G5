<?php
require_once './Databases/database.php';

class HistoryModel
{
    private $pdo;
    function __construct()
    {
        $this->pdo = new Database();
    }
    function getHistories()
    {
        $report = $this->pdo->query("SELECT * FROM reports ORDER BY id DESC");
        return $report->fetchAll();
    }

    function createHistories($data)
    {
        $this->pdo->query("INSERT INTO reports ( image ,product_id, product_name, quantity, price, total_price, created_at) VALUES ( :image, :product_id, :product_name, :quantity, :price, :total_price, :created_at)", [
            'image' => $data['image'],
            'product_id' => $data['product_id'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'total_price' => $data['total_price'],
            'created_at' => $data['created_at'],
        ]);
    }

    function getHistory($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM reports WHERE id = :id", ['id' => $id]);
        $user = $stmt->fetch();
        return $user;
    }
    function updateHistory($id, $data)
    {
        $this->pdo->query("UPDATE reports SET image = :image, name = :name WHERE id = :id", [
            'image' => $data['image'],
            'product_id' => $data['product_id'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'total_price' => $data['total_price'],
            'created_at' => $data['created_at'],
            'id' => $id
        ]);
    }
    function deleteHistory($id)
    {
        $this->pdo->query("DELETE FROM reports WHERE id = :id", ['id' => $id]);
    }

    function getFilteredHistories($filter, $startDate, $endDate, $search)
    {
        $query = "SELECT * FROM reports WHERE 1=1"; // Base query
        $params = [];

        // Apply date range only if provided (for 'all' filter with custom dates)
        if ($startDate && $endDate) {
            $query .= " AND created_at BETWEEN :start_date AND :end_date";
            $params['start_date'] = $startDate;
            $params['end_date'] = $endDate;
        }

        // Apply search filter
        if (!empty($search)) {
            $query .= " AND product_name LIKE :search";
            $params['search'] = "%$search%";
        }

        // Apply specific filters
        switch ($filter) {
            case 'today':
                $query .= " AND DATE(created_at) = CURDATE()";
                break;
            case 'this-week':
                $query .= " AND WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
                break;
            case 'last-week':
                $query .= " AND WEEK(created_at) = WEEK(CURDATE()) - 1 AND YEAR(created_at) = YEAR(CURDATE())";
                break;
            case 'this-month':
                $query .= " AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
                break;
            case 'last-month':
                $query .= " AND MONTH(created_at) = MONTH(CURDATE()) - 1 AND YEAR(created_at) = YEAR(CURDATE())";
                break;
        }

        $query .= " ORDER BY id DESC";
        $stmt = $this->pdo->query($query, $params);
        return $stmt->fetchAll();
    }
}
