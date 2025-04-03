<?php
require_once './Models/HistoryModel.php';
 
class HistoryController extends BaseController {
    public function index() {
        // Sample data (replace with database query)
        $history = [
            ['id' => 1, 'image' => 'https://via.placeholder.com/50', 'name' => 'Sample Product 1', 'quantity' => 10, 'price' => 25.00],
            ['id' => 2, 'image' => 'https://via.placeholder.com/50', 'name' => 'Sample Product 2', 'quantity' => 5, 'price' => 15.00],
        ];
        // Pass data to a view (assuming a simple templating system)
        require_once 'views/histories/list.php';
    }

    public function delete($id) {
        // Logic to delete history entry with $id
        header('Location: /history'); // Redirect back to history page
        exit;
    }
}