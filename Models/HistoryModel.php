<?php
require_once './Databases/database.php';

class HistoryModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new Database();
    }

    function getHistories($page = 1, $perPage = 25)
    {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT * FROM reports ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->query($query, [
            'limit' => $perPage,
            'offset' => $offset
        ]);
        return $stmt->fetchAll();
    }

    function createHistories($data)
    {
        $this->pdo->query("INSERT INTO reports (image, product_id, product_name, quantity, price, total_price, created_at) VALUES (:image, :product_id, :product_name, :quantity, :price, :total_price, :created_at)", [
            'image' => $data['image'],
            'product_id' => $data['product_id'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'total_price' => $data['total_price'],
            'created_at' => $data['created_at'],
        ]);
    }

    function getHistory($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM reports WHERE id = :id", ['id' => $id]);
        return $stmt->fetch();
    }

    function updateHistory($id, $data)
    {
        $this->pdo->query("UPDATE reports SET image = :image, product_id = :product_id, product_name = :product_name, quantity = :quantity, price = :price, total_price = :total_price, created_at = :created_at WHERE id = :id", [
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
        try {
            $record = $this->getHistory($id);
            if ($record && !empty($record['image']) && file_exists($record['image'])) {
                unlink($record['image']);
            }
            $this->pdo->query("DELETE FROM reports WHERE id = :id", ['id' => $id]);
        } catch (Exception $e) {
            error_log('Delete Error: ' . $e->getMessage());
            throw $e;
        }
    }

    function getFilteredHistories($filter, $startDate, $endDate, $search, $page = 1, $perPage = 25)
    {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT * FROM reports WHERE 1=1";
        $params = [];

        if ($startDate && $endDate) {
            $query .= " AND created_at BETWEEN :start_date AND :end_date";
            $params['start_date'] = $startDate;
            $params['end_date'] = $endDate;
        }

        if (!empty($search)) {
            $query .= " AND product_name LIKE :search";
            $params['search'] = "%$search%";
        }

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

        $query .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $perPage;
        $params['offset'] = $offset;

        $stmt = $this->pdo->query($query, $params);
        return $stmt->fetchAll();
    }

    function getTotalHistories($filter, $startDate, $endDate, $search)
    {
        $query = "SELECT COUNT(*) as total FROM reports WHERE 1=1";
        $params = [];

        if ($startDate && $endDate) {
            $query .= " AND created_at BETWEEN :start_date AND :end_date";
            $params['start_date'] = $startDate;
            $params['end_date'] = $endDate;
        }

        if (!empty($search)) {
            $query .= " AND product_name LIKE :search";
            $params['search'] = "%$search%";
        }

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

        $stmt = $this->pdo->query($query, $params);
        return $stmt->fetch()['total'];
    }

    function getProfit_Loss($page = 1, $itemsPerPage = 25)
    {
        $offset = ($page - 1) * $itemsPerPage;
        $stmt = $this->pdo->query("SELECT * FROM sales_data ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->execute([
            'limit' => $itemsPerPage,
            'offset' => $offset
        ]);
        return $stmt->fetchAll();
    }

    function getProfit_Loss_Count()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM sales_data");
        return $stmt->fetchColumn();
    }
}