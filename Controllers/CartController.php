
<?php require_once './Models/InventoryModel.php
class CartController {
    public function submit() {
        // Handle cart data submission
        $cart = json_decode(file_get_contents('php://input'), true);
        // Do something with the cart data (e.g., store in the database)
        if ($this->saveCart($cart)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save cart']);
        }
    }
}