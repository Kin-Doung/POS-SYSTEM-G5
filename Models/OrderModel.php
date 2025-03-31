<?php
// update-price.php

require_once './models/ProductModel.php';  // Include your ProductModel

// Get the data from the request body
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['price'])) {
    $productId = $data['id'];
    $newPrice = $data['price'];

    // Create an instance of the ProductModel class
    $productModel = new ProductModel();

    // Update the product price
    $result = $productModel->updateProductPrice($productId, $newPrice);

    // Send the appropriate response
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update price']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}
?>
