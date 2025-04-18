<?php
// File: CategoryController.php
require_once 'Models/CategoryModel.php';

class CategoryController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new CategoryModel();
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
                'name' => htmlspecialchars(trim($_POST['name'])),
            ];
            $this->model->createCategory($data);
            $this->redirect('/category');
        }
    }

    function edit($id)
    {
        $category = $this->model->getCategoryById($id);
        if ($category) {
            $this->views('categories/edit', ['category' => $category]);
        } else {
            die('Category not found');
        }
    }

    function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            error_log('Update POST: ' . print_r($_POST, true)); // Debug
            if (!isset($_POST['id']) || !isset($_POST['name'])) {
                error_log('Missing id or name in POST');
                die('Invalid form data: ID or name missing');
            }
            $id = (int)$_POST['id'];
            $data = [
                'name' => htmlspecialchars(trim($_POST['name'])),
            ];
            if ($this->model->updateCategory($id, $data)) {
                $this->redirect('/category?updated=true');
            } else {
                error_log('Update failed for ID: ' . $id);
                die('Failed to update category ID: ' . $id);
            }
        } else {
            die('Invalid request method');
        }
    }

    public function delete()
    {
        if (isset($_GET['id'])) {
            $categoryId = (int)$_GET['id'];
            if ($this->model->deleteCategory($categoryId)) {
                header('Location: /category?deleted=true');
                exit();
            } else {
                header('Location: /category?error=category_in_use');
                exit();
            }
        } else {
            header('Location: /category?error=no_id');
            exit();
        }
    }
}