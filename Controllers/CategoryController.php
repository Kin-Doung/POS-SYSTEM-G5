<?php
require_once 'Models/CategoryModel.php';
class CategoryController extends BaseController
{
    private $model;
    function __construct()
    {
        $this->model =  new CategoryModel();
    }
    function index()
    {
        $category = $this->model->getCategory();
        $this->views('categories/list', ['categories' => $category]);
    }

    function create()
    {
        $this->views('categories/create');
    }

    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => $_POST['name'],
            ];
            $this->model->createCategory($data);
            $this->redirect('/category');
        }
    }

    function edit($id)
    {
        $category = $this->model->getCategorys($id);
        $this->views('categories/edit', ['category' => $category]); 
    }
    

    function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => $_POST['name'],
                'category_id' => $_POST['category_id'],
            ];
            $this->model->updateCategory($id, $data);
            $this->redirect('/category');
        }
    }
    

    public function delete()
    {
        if (isset($_GET['id'])) {
            $categoryId = $_GET['id'];
            $categoryModel = new CategoryModel();
            
            if ($categoryModel->deleteCategory($categoryId)) {
                // After deletion, redirect to the category list page
                header('Location: /category');  // Adjust the URL if needed
                exit();
            } else {
                // Handle failure if category deletion fails
                echo "Error: Unable to delete category.";
            }
        } else {
            // Handle invalid ID
            echo "Error: Invalid category ID.";
        }
    }
    
    
    
    
}
