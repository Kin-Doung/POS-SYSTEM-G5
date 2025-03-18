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
            ];
            $this->model->updateCategory($id, $data);
            $this->redirect('/category');
        }
    }

    function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->model->deleteCategory($id);
            $this->redirect('/category');
        } else {
            echo "Invalid request.";
        }
    }
    
}
