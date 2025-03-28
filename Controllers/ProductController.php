<?php
require_once 'Models/ProductModel.php';
require_once './Models/CategoryModel.php';
require_once './Models/InventoryModel.php';

class ProductController extends BaseController
{
    private $model;

    public function __construct()
    {
        // Initialize the model
        $this->model = new ProductModel();
    }

    // Display all products
    public function index()
    {
        // Fetch data from models
        $inventory = $this->model->getInventoryWithProductDetails();
        $categories = $this->model->getCategories(); // Fetch categories here
        $products = $this->model->getProducts(); // Fetch products as well

        // Pass the fetched data to the view
        $this->views('products/list', [
            'inventory' => $inventory,
            'categories' => $categories, // Pass categories data
            'products' => $products // Pass products data
        ]);
    }



    // Store category data into the products table
    // public function storeCategoryDataToProducts()
    // {
    //     // Step 1: Retrieve all categories
    //     $categories = $this->model->getCategories();

    //     // Step 2: Loop through categories and insert products
    //     foreach ($categories as $category) {
    //         // Step 3: Define the product data
    //         $productData = [
    //             'category_id' => $category['id'],  // Link product to category
    //             'name' => "Product for " . $category['name'],  // Example product name
    //             'barcode' => "barcode-" . $category['id'],  // Example barcode
    //             'price' => 100,  // Example price
    //             'purchase_id' => null,  // You can set a purchase ID if needed
    //             'quantity' => 10,  // Example quantity
    //             'image' => null,  // Image can be set later if available
    //         ];

    //         // Step 4: Insert product data into the products table
    //         $this->model->createProduct($productData);
    //     }

    //     // Redirect or show success message after storing data
    //     header("Location: /products");
    // }

    // Store product data from inventory to products table
    public function storeInventoryToProducts()
    {
        // Step 1: Retrieve inventory data (which includes category_name)
        $inventoryItems = $this->model->getInventoryWithCategory();

        // Step 2: Retrieve categories to map category names to IDs
        $categories = $this->model->getCategories();
        $categoryMap = [];

        // Create a map of category names to IDs for faster lookup
        foreach ($categories as $category) {
            $categoryMap[$category['name']] = $category['id'];
        }

        // Step 3: Loop through inventory items and insert products into products table
        foreach ($inventoryItems as $item) {
            if (empty($item['inventory_product_name']) || empty($item['category_name'])) {
                continue;  // Skip if product name or category name is missing
            }

            // Step 4: Check if product already exists
            $existingProduct = $this->model->getProductByNameAndCategory($item['inventory_product_name'], $item['category_name']);

            if ($existingProduct) {
                // Update the product if it exists
                $this->model->updateProductFromInventory($existingProduct['id'], [
                    'category_id' => $categoryMap[$item['category_name']],
                    'price' => $item['amount'],
                    'quantity' => $item['quantity'],
                    'image' => $item['image'],
                ]);
            } else {
                // If the product doesn't exist, insert it
                if (isset($categoryMap[$item['category_name']])) {
                    $categoryId = $categoryMap[$item['category_name']];
                    $this->model->createProduct([
                        'category_id' => $categoryId,
                        'name' => $item['inventory_product_name'],
                        'barcode' => $item['barcode'] ?? null,
                        'price' => $item['amount'],
                        'purchase_id' => $item['purchase_id'] ?? null,
                        'quantity' => $item['quantity'],
                        'image' => $item['image'],
                    ]);
                }
            }
        }

        // Redirect or show success message after storing data
        header("Location: /products");
    }
}
