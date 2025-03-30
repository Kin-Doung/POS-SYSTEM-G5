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

    // Show all purchases
    function index()
    {
        $purchases = $this->model->getPurchases();
        $this->views('purchase/list', ['purchases' => $purchases]);
    }


    
    // Show the form to create a new purchase
// In PurchaseController.php
function create()
{
    $categories = $this->model->getCategories();
    
    // Get inventory items for selection
    $inventoryModel = new InventoryModel();
    $inventoryItems = $inventoryModel->getInventoryWithCategory();
    
    $this->views('purchase/create', [
        'categories' => $categories,
        'inventoryItems' => $inventoryItems
    ]);
}


    public function updateInline()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
    
        $id = $_POST['id'] ?? null;
        $field = $_POST['field'] ?? null;
        $value = $_POST['value'] ?? null;
    
        if (!$id || !$field || $value === null) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            return;
        }
    
        try {
            $this->model->updateField($id, $field, $value);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Add bulk destroy method
    public function bulkDestroy()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $ids = $input['ids'] ?? [];

        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'No items selected']);
            return;
        }

        try {
            $this->model->startTransaction();
            $this->model->bulkDelete($ids);
            $this->model->commitTransaction();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $this->model->rollBackTransaction();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }


        $this->model->startTransaction();

        try {
            $product_names = $_POST['product_name'];
            $category_ids = $_POST['category_id'];
            $quantities = $_POST['quantity'];
            $amounts = $_POST['amount'];
            $type_of_products = $_POST['typeOfproducts'];

            foreach ($category_ids as $category_id) {
                if (!$this->model->categoryExists($category_id)) {
                    $this->model->rollBackTransaction();
                    $this->redirect('/purchase/create', 'Invalid category!');
                    return;
                }
            }

            foreach ($product_names as $index => $product_name) {
                if (
                    empty($product_name) || !is_numeric($category_ids[$index]) ||
                    !is_numeric($quantities[$index]) || !is_numeric($amounts[$index])
                ) {
                    $this->model->rollBackTransaction();
                    $this->redirect('/purchase/create', "Invalid input at index $index!");
                    return;
                }

                // Handle image upload
                $imageData = null;
                if (
                    isset($_FILES['image']['tmp_name'][$index]) &&
                    is_uploaded_file($_FILES['image']['tmp_name'][$index])
                ) {
                    $imageData = file_get_contents($_FILES['image']['tmp_name'][$index]);
                    if ($imageData === false) {
                        $this->model->rollBackTransaction();
                        $this->redirect('/purchase/create', "Failed to read image at index $index!");
                        return;
                    }
                }

                $this->model->insertProduct(
                    $product_name,
                    $category_ids[$index],
                    $quantities[$index],
                    $amounts[$index],
                    $type_of_products[$index],
                    $imageData
                );
            }

            $this->model->commitTransaction();
            $this->redirect('/purchase', 'Purchase added successfully!');
        } catch (Exception $e) {
            $this->model->rollBackTransaction();
            $this->redirect('/purchase/create', 'Error: ' . $e->getMessage());
        }
    }

    function edit($id)
    {
        $purchase = $this->model->getPurchase($id);
        if (!$purchase) {
            $this->redirect('/purchase', 'Purchase not found.');
            return;
        }
    
        $categories = $this->model->getCategories();
        $this->views('purchase/edit', ['purchase' => $purchase, 'categories' => $categories]);
    }
    
    function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("/purchase/edit/$id", 'Invalid request method.');
            return;
        }
    
        $purchase = $this->model->getPurchase($id);
        if (!$purchase) {
            $this->redirect('/purchase', 'Purchase not found.');
            return;
        }
    
        $imageData = $purchase['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            if ($imageData === false) {
                $this->redirect("/purchase/edit/$id", 'Failed to read image.');
                return;
            }
        }
    
        $data = [
            'product_name' => htmlspecialchars($_POST['product_name']),
            'category_id' => intval($_POST['category_id']),
            'quantity' => intval($_POST['quantity']),
            'price' => floatval($_POST['price']),
            'type_of_product' => htmlspecialchars($_POST['type_of_product']),
            'image' => $imageData
        ];
    
        try {
            $this->model->updatePurchase($id, $data);
            $this->redirect('/purchase', 'Purchase updated successfully!');
        } catch (Exception $e) {
            $this->redirect("/purchase/edit/$id", 'Error: ' . $e->getMessage());
        }
    }
    // Update purchase

    public function store()
    {
        if (!isset($_POST['product_name'], $_POST['category_id'], $_POST['quantity'], $_POST['amount'], $_POST['typeOfproducts'])) {
            $this->redirect('/purchase/create', 'Missing data!');
            return;
        }

        $this->model->startTransaction();

        try {
            $product_names = $_POST['product_name'];
            $category_ids = $_POST['category_id'];
            $quantities = $_POST['quantity'];
            $amounts = $_POST['amount'];
            $type_of_products = $_POST['typeOfproducts'];

            foreach ($category_ids as $category_id) {
                if (!$this->model->categoryExists($category_id)) {
                    $this->model->rollBackTransaction();
                    $this->redirect('/purchase/create', 'Invalid category!');
                    return;
                }
            }

            foreach ($product_names as $index => $product_name) {
                if (
                    empty($product_name) || !is_numeric($category_ids[$index]) ||
                    !is_numeric($quantities[$index]) || !is_numeric($amounts[$index])
                ) {
                    $this->model->rollBackTransaction();
                    $this->redirect('/purchase/create', "Invalid input at index $index!");
                    return;
                }

                $imageData = null;
                if (
                    isset($_FILES['image']['tmp_name'][$index]) &&
                    is_uploaded_file($_FILES['image']['tmp_name'][$index])
                ) {
                    $imageData = file_get_contents($_FILES['image']['tmp_name'][$index]);
                    if ($imageData === false) {
                        $this->model->rollBackTransaction();
                        $this->redirect('/purchase/create', "Failed to read image at index $index!");
                        return;
                    }
                }

                $this->model->insertProduct(
                    $product_name,
                    $category_ids[$index],
                    $quantities[$index],
                    $amounts[$index],
                    $type_of_products[$index],
                    $imageData
                );
            }

            $this->model->commitTransaction();
            $this->redirect('/purchase', 'Purchase added successfully!');
        } catch (Exception $e) {
            $this->model->rollBackTransaction();
            $this->redirect('/purchase/create', 'Error: ' . $e->getMessage());
        }
    }


    // Delete purchase
    // In PurchaseController.php
    function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/purchase', 'Invalid request method.');
            return;
        }

        $purchase = $this->model->getPurchase($id);
        if (!$purchase) {
            $this->redirect('/purchase', 'Purchase not found.');
            return;
        }

        try {
            $this->model->deletePurchase($id);
            $this->redirect('/purchase', 'Purchase deleted successfully!');
        } catch (Exception $e) {
            $this->redirect('/purchase', 'Error deleting purchase: ' . $e->getMessage());
        }
    }

    // Handle image upload
    private function handleImageUpload()
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileMimeType = mime_content_type($_FILES['image']['tmp_name']);

            if (!in_array($fileMimeType, $allowedMimeTypes)) {
                $this->redirect($_SERVER['HTTP_REFERER'], 'Invalid file type! Only JPG, PNG, and GIF allowed.');
                return null;
            }

            $uploadDir = __DIR__ . '/../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $newImagePath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $newImagePath)) {
                return $newImagePath; // Return the new image path
            } else {
                $this->redirect($_SERVER['HTTP_REFERER'], 'Image upload failed.');
            }
        }
        return null; // Return null if no image is uploaded
    }

    // Redirect function with message
    public function redirect($url, $message = '')
    {
        if ($message) {
            $_SESSION['message'] = $message;
        }
        header("Location: $url");
        exit();
    }
}
