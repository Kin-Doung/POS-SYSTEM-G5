<?php
require_once 'Models/SettingModel.php';
require_once 'BaseController.php';
class SettingController extends BaseController{
    private $model;

    public function __construct() {
        $this->model = new SettingModel();
    }

    // Display admin settings
    public function index() {
        $admins = $this->model->getAdminUsers();
        require_once 'views/settings/list.php'; // Ensure this path is correct
    }

    // Edit admin info
    public function edit($id) {
        $admin = $this->model->getAdmin($id);
        require_once 'views/settings/edit.php'; // Load edit page
    }

    // Update admin info
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $admin = $this->model->getAdmin($id);

            $imageData = $admin['store_logo']; // Keep old logo if not changed
            if (isset($_FILES['store_logo']) && $_FILES['store_logo']['error'] === UPLOAD_ERR_OK) {
                $imageData = file_get_contents($_FILES['store_logo']['tmp_name']);
            }

            $password = !empty($_POST['password']) 
                ? password_hash($_POST['password'], PASSWORD_DEFAULT) 
                : $admin['password']; // Keep old password if not updated

            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $password,
                'store_name' => $_POST['store_name'],
                'store_logo' => $imageData,
                'language' => $_POST['language']
            ];

            $this->model->updateAdmin($id, $data);

            // Redirect to settings page
            header("Location: /settings");
            exit();
        }
    }
}
