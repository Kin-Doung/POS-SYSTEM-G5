<?php
require_once './Models/HistoryModel.php';

class HistoryController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new HistoryModel();
    }

    function index($page = 1)
    {
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
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                header('Content-Type: application/json', true, 403);
                echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
                exit;
            }

            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png'];
                if (!in_array($_FILES['image']['type'], $allowedTypes) || $_FILES['image']['size'] > 2 * 1024 * 1024) {
                    header('Content-Type: application/json', true, 400);
                    echo json_encode(['success' => false, 'error' => 'Invalid or too large image']);
                    exit;
                }
                $uploadDir = 'Uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $imagePath = $uploadDir . $imageName;
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    error_log('File upload failed for ' . $_FILES['image']['name']);
                    $imagePath = null;
                }
            }

            // Validate inputs
            $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_FLOAT);
            $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
            if ($quantity === false || $price === false || $quantity <= 0 || $price < 0) {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['success' => false, 'error' => 'Invalid quantity or price']);
                exit;
            }

            // Calculate total_price with precision
            $totalPrice = bcmul($quantity, $price, 2);

            $data = [
                'product_id' => filter_var($_POST['product_id'], FILTER_SANITIZE_STRING),
                'product_name' => filter_var($_POST['product_name'], FILTER_SANITIZE_STRING),
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $totalPrice,
                'created_at' => $_POST['created_at'] ?? date('Y-m-d H:i:s'),
                'image' => $imagePath
            ];

            try {
                $this->model->createHistories($data);
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            } catch (Exception $e) {
                error_log('Store Error: ' . $e->getMessage());
                header('Content-Type: application/json', true, 500);
                echo json_encode(['success' => false, 'error' => 'Failed to create record: ' . $e->getMessage()]);
                exit;
            }
        }
    }

    function edit($id)
    {
        $report = $this->model->getHistory($id);
        if ($report) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'report' => $report]);
        } else {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['success' => false, 'error' => 'Record not found']);
        }
        exit;
    }

    function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verify CSRF token
            $headers = getallheaders();
            $csrfToken = $headers['X-CSRF-Token'] ?? '';
            if ($csrfToken !== ($_SESSION['csrf_token'] ?? '')) {
                header('Content-Type: application/json', true, 403);
                echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
                exit;
            }

            try {
                $report = $this->model->getHistory($id);
                if (!$report) {
                    throw new Exception('Record not found');
                }

                // Handle image upload
                $imagePath = $report['image'];
                if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/jpeg', 'image/png'];
                    if (!in_array($_FILES['image']['type'], $allowedTypes) || $_FILES['image']['size'] > 2 * 1024 * 1024) {
                        throw new Exception('Invalid or too large image');
                    }
                    $uploadDir = 'Uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $imageName = time() . '_' . basename($_FILES['image']['name']);
                    $imagePath = $uploadDir . $imageName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                        if ($report['image'] && file_exists($report['image'])) {
                            unlink($report['image']);
                        }
                    } else {
                        $imagePath = $report['image'];
                    }
                }

                // Validate inputs
                $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_FLOAT);
                $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
                if ($quantity === false || $price === false || $quantity <= 0 || $price < 0) {
                    throw new Exception('Invalid quantity or price');
                }

                // Calculate total_price with precision
                $totalPrice = bcmul($quantity, $price, 2);

                $data = [
                    'product_id' => filter_var($_POST['product_id'] ?? $report['product_id'], FILTER_SANITIZE_STRING),
                    'product_name' => filter_var($_POST['product_name'], FILTER_SANITIZE_STRING),
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                    'created_at' => $_POST['created_at'] ?? $report['created_at'],
                    'image' => $imagePath
                ];

                $this->model->updateHistory($id, $data);
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            } catch (Exception $e) {
                error_log('Update Error: ' . $e->getMessage());
                header('Content-Type: application/json', true, 500);
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                exit;
            }
        }
    }

    function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Verify CSRF token
            $headers = getallheaders();
            $csrfToken = $headers['X-CSRF-Token'] ?? '';
            if ($csrfToken !== ($_SESSION['csrf_token'] ?? '')) {
                header('Content-Type: application/json', true, 403);
                echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
                exit;
            }

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
                error_log('Destroy Error: ' . $e->getMessage());
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
            $startDate = $_POST['start_date'] ?? null;
            $endDate = $_POST['end_date'] ?? null;
            $search = $_POST['search'] ?? '';
            $page = $_POST['page'] ?? 1;
            $perPage = 25;

            try {
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
            } catch (Exception $e) {
                error_log('FetchFilteredHistories Error: ' . $e->getMessage());
                header('Content-Type: application/json', true, 500);
                echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
            }
            exit;
        }
    }
}