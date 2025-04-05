<?php
require_once './Models/Profit_LossModel.php';

class Profit_LossController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new Profit_LossModel();
    }

    // List all profit/loss records
    function index()
    {
        $profit_loss = $this->model->getProfit_Loss();
        $this->views('profit_loss/list', ['Profit_Loss' => $profit_loss]);
    }

    // Store new profit/loss record
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

            // Prepare the data for saving
            $data = [
                'Product_Name'  => $_POST['Product_Name'],
                'quantity'  => $_POST['quantity'],
                'Cost_Price'  => $_POST['Cost_Price'],
                'Selling_Price'  => $_POST['Selling_Price'],
                'Profit_Loss'  => $_POST['Profit_Loss'], // Corrected field name
                'Result_Type'  => $_POST['Result_Type'],
                'Sale_Date'  => $_POST['Sale_Date'],
                'product_id'  => $_POST['product_id'],
                'inventory_id'  => $_POST['inventory_id'],
                'image' => $imagePath
            ];

            // Save the new profit/loss data
            $this->model->createProfit_Loss($data);
            $this->redirect('/profit_loss');
        }
    }

    // Edit an existing profit/loss record
    function edit($id)
    {
        $profit_loss = $this->model->getProfit_Loss_By_Id($id); // Corrected method call
        $this->views('profit_loss/edit', ['Profit_Loss' => $profit_loss]);
    }

    // Delete a profit/loss record
    function destroy($id)
    {
        $this->model->deleteProfit_Loss($id);
        $this->redirect('/profit_loss');
    }
}
?>
