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
        header("Cache-Control: no-cache, must-revalidate"); // Prevent caching
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; // Get page from URL, default to 1
        $perPage = 25; // Show 5 cards per page
        $profit_loss = $this->model->getProfit_Loss($page, $perPage);
        $totalRecords = $this->model->getTotalRecords();
        $totalPages = ceil($totalRecords / $perPage); // Calculate total pages

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
                $uploadDir = 'uploads/';
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $imagePath = $uploadDir . $imageName;
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $imagePath = null;
                }
            }
            $data = [
                'Product_Name' => $_POST['Product_Name'],
                'quantity' => $_POST['quantity'],
                'Cost_Price' => $_POST['Cost_Price'],
                'Selling_Price' => $_POST['Selling_Price'],
                'Profit_Loss' => $_POST['Profit_Loss'],
                'Result_Type' => $_POST['Result_Type'],
                'Sale_Date' => $_POST['Sale_Date'],
                'product_id' => $_POST['product_id'],
                'inventory_id' => $_POST['inventory_id'],
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
        $this->model->deleteProfit_Loss($id);
        $this->redirect('/profit_loss');
    }

    function destroy_multiple()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $ids = $input['ids'] ?? [];
            header('Content-Type: application/json');
            if (empty($ids)) {
                error_log("Controller: No IDs received");
                echo json_encode(['success' => false, 'message' => 'No IDs provided']);
                exit;
            }
            error_log("Controller: Received IDs: " . implode(',', $ids));
            $ids = array_map('intval', $ids);
            try {
                $result = $this->model->deleteProfit_Loss($ids);
                error_log("Controller: Deletion result: " . ($result ? 'Success' : 'Failed'));
                echo json_encode([
                    'success' => $result,
                    'message' => $result ? 'Records deleted successfully' : 'No records deleted (IDs may not exist)',
                    'ids' => $ids // Return IDs for debugging
                ]);
            } catch (Exception $e) {
                error_log("Controller: Delete error: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }
}