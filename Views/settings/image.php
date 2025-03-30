<?php
require 'db_connection.php'; // Ensure this connects to your database

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $query = $conn->prepare("SELECT store_logo FROM admins WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
        $query->bind_result($store_logo);
        $query->fetch();

        // Check if image data exists
        if (!empty($store_logo)) {
            header("Content-Type: image/jpeg"); // Change this if your images are PNG
            echo $store_logo;
        } else {
            echo "Image is empty in database";
        }
    } else {
        echo "No Image Found";
    }
    $query->close();
} else {
    echo "Invalid request";
}
?>
