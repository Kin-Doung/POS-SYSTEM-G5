<?php
require_once __DIR__ . '/BaseController.php';
require_once './Models/TrackingModel.php';
require_once './Models/CategoryModel.php';

class NavController extends BaseController
{
    private $model;
    private $categories;

    function __construct()
    {
        $this->model = new TrackingModel();
        $this->categories = new CategoryModel();
    }

    // Show the tracking list
    function index()
    {
        $tracking = $this->model->getInventory();
        $categories = $this->model->getCategory();
    
        // Process images for display
        foreach ($tracking as &$item) {
            if ($item['image']) {
                $item['image_base64'] = 'data:image/jpeg;base64,' . base64_encode($item['image']);
            }
        }
        unset($item);
    
        $this->views('tracking/list', [
            'tracking' => $tracking,
            'categories' => $categories
        ]);
    }

    // Show the form to create a new tracking item
    function create()
    {
        $categories = $this->categories->getCategory();
        $this->views('tracking/create', ['categories' => $categories]);
    }

    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imagePaths = [];
            $categoryIds = $_POST['category_id'];
            $productNames = $_POST['product_name'];
            $quantities = $_POST['quantity'];
            $prices = $_POST['amount'];
            $expirationDates = $_POST['expiration_date'];

            foreach ($productNames as $index => $productName) {
                $imagePath = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'][$index] == 0) {
                    $targetDir = "uploads/";
                    $imageName = uniqid() . '-' . basename($_FILES['image']['name'][$index]);
                    $imagePath = $targetDir . $imageName;

                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (in_array($_FILES['image']['type'][$index], $allowedTypes) && $_FILES['image']['size'][$index] <= 2 * 1024 * 1024) {
                        if (!move_uploaded_file($_FILES['image']['tmp_name'][$index], $imagePath)) {
                            echo "Error uploading image.";
                            return;
                        }
                    } else {
                        echo "Invalid file type or file is too large.";
                        return;
                    }
                }

                $categoryId = $categoryIds[$index];
                $category = $this->categories->getCategoryById($categoryId);
                if (!$category) {
                    echo "Invalid category selected.";
                    return;
                }

                $data = [
                    'product_name' => $productNames[$index],
                    'category_id' => $categoryId,
                    'category_name' => $category['name'],
                    'quantity' => $quantities[$index],
                    'amount' => $prices[$index],
                    'total_price' => $quantities[$index] * $prices[$index],
                    'expiration_date' => $expirationDates[$index],
                    'image' => $imagePath ?? null,
                ];

                $this->model->createInventory($data);
            }
            $this->redirect('/tracking');
        }
    }
    
}