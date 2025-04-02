<?php
require_once 'Models/PurchaseModel.php';
require_once 'BaseController.php';

class PurchaseController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new PurchaseModel();
    }

    function index()
    {
        $purchases = $this->model->getPurchases();
        $this->views('purchase/list', ['purchases' => $purchases]);
    }

    function create()
    {
        $categories = $this->model->getCategories();
        $this->views('purchase/create', ['categories' => $categories]);
    }

    public function store()
    {
        if (!isset($_POST['category_id'], $_POST['product_name'])) {
            $this->redirect('/purchase/create', 'Missing data!');
            return;
        }

        $this->model->startTransaction();
        try {
            $category_ids = $_POST['category_id'];
            $product_names = $_POST['product_name'];
            $imagePaths = [];

            foreach ($category_ids as $index => $category_id) {
                $product_name = $product_names[$index] ?? null;
                $imagePath = null;

                if (!empty($_FILES['image']['name'][$index])) {
                    $uploadDir = "uploads/";
                    $fileName = time() . "_" . basename($_FILES['image']['name'][$index]);
                    $targetFilePath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'][$index], $targetFilePath)) {
                        $imagePath = $targetFilePath;
                    }
                }

                if (empty($category_id)) {
                    throw new Exception("Invalid input at index $index!");
                }

                $this->model->insertProduct($imagePath, $category_id, $product_name);
            }

            $this->model->commitTransaction();
            $this->redirect('/purchase', 'Purchase added successfully!');
        } catch (Exception $e) {
            $this->model->rollBackTransaction();
            $this->redirect('/purchase/create', 'Error: ' . $e->getMessage());
        }
    }

    function delete($id)
    {
        try {
            $this->model->deletePurchase($id);
            $this->redirect('/purchase', 'Purchase deleted successfully!');
        } catch (Exception $e) {
            $this->redirect('/purchase', 'Error: ' . $e->getMessage());
        }
    }

    public function redirect($url, $message = '')
    {
        if ($message) {
            $_SESSION['message'] = $message;
        }
        header("Location: $url");
        exit();
    }
}
?>
