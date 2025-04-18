<?php
require_once './Models/Profit_LossModel.php';

class Profit_LossController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new Profit_LossModel();
    }

    function index()
    {
        header("Cache-Control: no-cache, must-revalidate");
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 25;
        $profit_loss = $this->model->getProfit_Loss($page, $perPage);
        $totalRecords = $this->model->getTotalRecords();
        $totalPages = ceil($totalRecords / $perPage);

        $this->views('profit_loss/list', [
            'Profit_Loss' => $profit_loss,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage
        ]);
    }

    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = 'Uploads/';
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $imagePath = $uploadDir . $imageName;
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $imagePath = null;
                }
            }
            $data = [
                'Product_Name' => $_POST['Product_Name'] ?? '',
                'quantity' => $_POST['quantity'] ?? 0,
                'Cost_Price' => $_POST['Cost_Price'] ?? 0,
                'Selling_Price' => $_POST['Selling_Price'] ?? 0,
                'Profit_Loss' => $_POST['Profit_Loss'] ?? 0,
                'Result_Type' => $_POST['Result_Type'] ?? '',
                'Sale_Date' => $_POST['Sale_Date'] ?? date('Y-m-d'),
                'product_id' => $_POST['product_id'] ?? 0,
                'inventory_id' => $_POST['inventory_id'] ?? 0,
                'image' => $imagePath
            ];
            $this->model->createProfit_Loss($data);
            $this->redirect('/profit_loss');
        }
    }

    function edit($id)
    {
        $profit_loss = $this->model->getProfit_Loss_By_Id($id);
        $this->views('profit_loss/edit', ['Profit_Loss' => $profit_loss]);
    }

    function destroy($id)
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' || !isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['csrf_token']) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }
        if (!is_numeric($id) || $id <= 0) {
            error_log("Controller: Invalid ID for single delete: " . $id);
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit;
        }
        error_log("Controller: Attempting to delete single ID: " . $id);
        try {
            $result = $this->model->deleteProfit_Loss($id);
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Record deleted successfully' : 'Record not found'
            ]);
        } catch (Exception $e) {
            error_log("Controller: Single delete error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error deleting record']);
        }
        exit;
    } 

    function destroy_multiple()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $ids = isset($input['ids']) ? (array) $input['ids'] : [];

        header('Content-Type: application/json');

        if (empty($ids)) {
            error_log("Controller: No IDs received for deletion");
            echo json_encode(['success' => false, 'message' => 'No items selected']);
            exit;
        }

        $ids = array_filter($ids, fn($id) => is_numeric($id) && $id > 0);
        $ids = array_map('intval', $ids);

        if (empty($ids)) {
            error_log("Controller: No valid IDs after filtering");
            echo json_encode(['success' => false, 'message' => 'No valid items selected']);
            exit;
        }

        error_log("Controller: Attempting to delete IDs: " . implode(',', $ids));

        try {
            $result = $this->model->deleteProfit_Loss($ids);
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Records deleted successfully' : 'No records deleted (items may not exist)',
                'ids' => $ids
            ]);
        } catch (Exception $e) {
            error_log("Controller: Delete error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error deleting records']);
        }
        exit;
    }
    function get_totals()
    {
        header('Content-Type: application/json');
        $totals = $this->model->getProfitLossTotals();
        echo json_encode($totals);
        exit;
    }
}
