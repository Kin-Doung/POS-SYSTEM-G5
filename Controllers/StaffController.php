<?php
require './Models/UserModel.php';
require_once 'BaseController.php';
class UserController extends BaseController
{
    private $model;
    function __construct(){
        $this->model =  new UserModel();
    }
    function index(){
        $users = $this->model->getUsers();
        $this->views('user/list.php', ['users' => $users]);
    }
    function create(){
        $this->views('user/create.php');
    }

    function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $imageData = null;
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

                $imageData = file_get_contents($_FILES['file']['tmp_name']);
            }
            $data = [
                'name' => $_POST['name'],
                'image' => $imageData
            ];
            $this->model->createUser($data);

            $this->redirect('/user');
        }
    }
    function edit($id){
        $user = $this->model->getUser($id);
        $this->views('user/edit.php', ['user' => $user]);
    }
    function update($id){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->model->getUser($id);


            $imageData = $user['image'];
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $imageData = file_get_contents($_FILES['file']['tmp_name']);
            }
            $data = [
                'name' => $_POST['name'],
                'image' => $imageData
            ];

            $this->model->updateUser($id, $data);
            $this->redirect('/user');
        }
    }

    function destroy($id){
        $this->model->deleteUser($id);
        $this->redirect('/user');
    }
}
