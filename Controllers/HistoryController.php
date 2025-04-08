<?php
require_once './Models/HistoryModel.php';


class HistoryController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new HistoryModel();
    }

    function index()
    {
        $report = $this->model->getHistories();
        $this->views('histories/list', ['reports' => $report]);
    }



    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $imagePath = $uploadDir . $imageName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $imagePath = null; // If upload fails, set to null
                }
            }

            $data = [
                'product_id'  => $_POST['product_id'],
                'product_name'  => $_POST['product_name'],
                'quantity'  => $_POST['quantity'],
                'price'  => $_POST['price'],
                'total_price'  => $_POST['total_price'],
                'created_at'  => $_POST['created_at'],
                'image' => $imagePath
            ];
        }
    }

    function edit($id)
    {
        $report = $this->model->getHistory($id);
        $this->views('histories/edit', ['reports' => $report]);
    }

    function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $report = $this->model->getHistory($id);
            $imagePath = $report['image'];
            $data = [
                'product_id'  => $_POST['product_id'],
                'product_name'  => $_POST['product_name'],
                'quantity'  => $_POST['quantity'],
                'price'  => $_POST['price'],
                'total_price'  => $_POST['natotal_priceme'],
                'created_at'  => $_POST['created_at'],
                'image' => $imagePath
            ];

            $this->model->updateHistory($id, $data);
            $this->redirect('/histories');
        }
    }
    function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            try {
                $record = $this->model->getHistory($id);
                if (!$record) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => 'Record not found']);
                    exit;
                }
                $this->model->deleteHistory($id);
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                header('Content-Type: application/json', true, 500);
                echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
            }
            exit;
        }
    }
    function fetchFilteredHistories()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $filter = $_POST['filter'] ?? 'all';
            $startDate = $_POST['start_date'] ?? '2000-01-01';
            $endDate = $_POST['end_date'] ?? '2099-12-31';
            $search = $_POST['search'] ?? '';

            $reports = $this->model->getFilteredHistories($filter, $startDate, $endDate, $search);
            $totalPrice = array_sum(array_column($reports, 'total_price'));

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'reports' => $reports,
                'total_price' => number_format($totalPrice, 2)
            ]);
            exit;
        }
    }
}
