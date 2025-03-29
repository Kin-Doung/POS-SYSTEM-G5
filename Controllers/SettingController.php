<?php
require_once 'Models/SettingModel.php';
require_once 'BaseController.php';

class SettingController extends BaseController
{
    private $model;
    private $admins;

    public function __construct()
    {
        $this->model = new SettingModel();
    }

    // Display all admin settings (index)
    public function index()
    {
        $admins = $this->model->getAdminUsers();
        require_once 'views/settings/list.php';
        // require_once 'views/settings/account.php';
    }

    // Show form to create a new admin (create)
    // public function create()
    // {
    //     require_once 'views/settings/create.php';
    // }

    // Store new admin data (store)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $store_logo = null; // Default value

            // Check if a file was uploaded
            if (!empty($_FILES['store_logo']['tmp_name'])) {
                $imageData = file_get_contents($_FILES['store_logo']['tmp_name']);
                $store_logo = base64_encode($imageData); // Convert to base64
            }

            // Prepare data for database
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'store_name' => $_POST['store_name'],
                'store_logo' => $store_logo, // Store base64 image
                'language' => $_POST['language'],
            ];

            // Save to database
            if ($this->model->saveAdmin($data)) {
                header("Location: /settings");
                exit();
            } else {
                echo "Error updating admin settings.";
            }
        }
    }

    // Edit admin info (edit)

    public function edit($id)
    {
        $admin = $this->model->getAdmin($id);

        if (!$admin) {
            die("Admin not found!"); // Debugging message
        }

        require_once 'views/settings/edit.php';
    }


    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $admin = $this->model->getAdmin($id);
            $imageData = $admin['store_logo'];

            if (!empty($_FILES['store_logo']['tmp_name'])) {
                $imageData = file_get_contents($_FILES['store_logo']['tmp_name']);
            }

            $password = !empty($_POST['password'])
                ? password_hash($_POST['password'], PASSWORD_DEFAULT)
                : $admin['password'];

            $data = [
                'id' => $id,
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $password,
                'store_name' => $_POST['store_name'],
                'store_logo' => $imageData,
                'language' => $_POST['language']
            ];

            $this->model->updateAdmin($data);
            header("Location: /settings");
            exit();
        }
    }


    // Delete admin (destroy)
    public function destroy($id)
    {
        $this->model->deleteAdmin($id);
        header("Location: /settings");
        exit();
    }
}
