<?php
require_once './Models/HistoryModel.php';

class HistoryController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new HistoryModel();
    }
    function index($page = 1) {
        $perPage = 25;
        $report = $this->model->getHistories($page, $perPage);
        $total = $this->model->getTotalHistories('all', null, null, '');
        $totalPages = ceil($total / $perPage);
        $this->views('histories/list', [
            'reports' => $report,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $imagePath = $uploadDir . $imageName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $imagePath = null;
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
            // Add this to complete the method
            $this->model->createHistories($data);
            $this->redirect('/histories');
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
                'total_price'  => $_POST['total_price'], // Fixed typo from 'natotal_priceme'
                'created_at'  => $_POST['created_at'],
                'image' => $imagePath
            ];

            $this->model->updateHistory($id, $data);
            $this->redirect('/histories');
        }
    }

    function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            $ids = $data['ids'] ?? [];

            if (empty($ids)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'No IDs provided']);
                exit;
            }

            try {
                foreach ($ids as $id) {
                    $record = $this->model->getHistory($id);
                    if ($record) {
                        $this->model->deleteHistory($id);
                    }
                }
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                header('Content-Type: application/json', true, 500);
                echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
            }
            exit;
        }
    }

    function fetchFilteredHistories() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $filter = $_POST['filter'] ?? 'all';
            $startDate = $_POST['start_date'] ?? '2000-01-01';
            $endDate = $_POST['end_date'] ?? '2099-12-31';
            $search = $_POST['search'] ?? '';
            $page = $_POST['page'] ?? 1;
            $perPage = 25;

            $reports = $this->model->getFilteredHistories($filter, $startDate, $endDate, $search, $page, $perPage);
            $total = $this->model->getTotalHistories($filter, $startDate, $endDate, $search);
            $totalPrice = array_sum(array_column($reports, 'total_price'));
            $totalPages = ceil($total / $perPage);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'reports' => $reports,
                'total_price' => number_format($totalPrice, 2),
                'currentPage' => (int)$page,
                'totalPages' => $totalPages
            ]);
            exit;
        }
    }
}