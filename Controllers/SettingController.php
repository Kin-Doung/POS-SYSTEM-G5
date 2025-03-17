<?php
require_once 'Models/SettingModel.php';

class SettingController extends BaseController{
 
    function index()
    {
        $this->views('settings/list');
    }
}
// class SettingsController {
//     public function index() {
//         require_once "./Views/settings/index.php"; // Load the settings view
//     }

//     public function updateProfile() {
//         require_once "./Databases/Database.php"; // Include database connection
//         session_start();
//         $userID = $_SESSION['user_id'];

//         if ($_SERVER["REQUEST_METHOD"] == "POST") {
//             $name = $_POST['name'];
//             $email = $_POST['email'];

//             // Handle Profile Picture Upload
//             $profilePic = $_FILES['profilePic']['name'] ? "uploads/" . basename($_FILES["profilePic"]["name"]) : $_POST['existingPic'];
//             if ($_FILES['profilePic']['name']) {
//                 move_uploaded_file($_FILES["profilePic"]["tmp_name"], $profilePic);
//             }

//             // Update Database
//             $sql = "UPDATE users SET name=?, email=?, profile_pic=? WHERE id=?";
//             $stmt = $conn->prepare($sql);
//             $stmt->bind_param("sssi", $name, $email, $profilePic, $userID);
//             $stmt->execute();
//             header("Location: index.php?page=settings");
//         }
//     }
// }
// ?>
