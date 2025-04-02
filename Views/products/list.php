<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="search-container" style="background-color: #fff;">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search..." />
        </div>
        <div class="icons">
            <i class="fas fa-globe icon-btn"></i>
            <div class="icon-btn" id="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notification-count">8</span>
            </div>
        </div>
        <div class="profile">
            <img src="../../views/assets/images/image.png" alt="User">
            <div class="profile-info">
                <span>Eng Ly</span>
                <span class="store-name">Owner Store</span>
            </div>
        </div>
    </nav>

    <!-- Search and Category Filter -->
    <div class="input-group">
        <input type="text" id="searchInput" class="form-control input-group-search" placeholder="Search...">
        <select id="categorySelect" class="ms-2 selected">
            <option value="">Select Category</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option disabled>No Categories Found</option>
            <?php endif; ?>
        </select>
    </div>

    <!-- Product Card Section -->
    <div class="container mt-5 d-flex">
        <div class="product-list flex-grow-1">
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-6 col-sm-4 col-md-3 mb-4">
                        <div class="card square-card">
                            <div class="image-wrapper">
                                <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="card-body">
                                <h6 class="card-title text-center"><?= htmlspecialchars($product['name']) ?></h6>
                                <p class="card-text text-center price"><?= htmlspecialchars($product['price']) ?> $</p>
                                <div class="text-center mt-2">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>" />
                                    <button class="buy">Buy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="cart-section ms-4 p-3 border rounded shadow bg-white" id="cartSection" style="width: 500px; display: none;">
            <h4>Cart</h4>
            <table class="table table-bordered text-center" id="cartTable">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Total ($)</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <h5>Total: <span id="grandTotal">0</span> $</h5>
            <div class="text-center mt-3">
                <button class="btn btn-success" onclick="replaceCartInDatabase()">Submit</button>
            </div>
        </div>
    </div>

    <!-- Styles -->

    <!-- JavaScript -->
    <script>
        function replaceCartInDatabase() {
            const cartTable = document.getElementById('cartTable').querySelector('tbody');
            const cartItems = [];
            cartTable.querySelectorAll('tr').forEach(row => {
                const productId = row.querySelector('input[name="product_id"]').value;
                const quantity = parseInt(row.querySelector('td:nth-child(2)').textContent); // Qty column
                cartItems.push({
                    product_id: productId,
                    quantity: quantity
                });
            });

            fetch('/product/submitCart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(cartItems)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('okay'); // Show "okay" on successful submission
                    } else {
                        alert('Error: ' + data.message); // Show error message if it fails
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

    <?php require_once 'views/layouts/footer.php'; ?>
    <style>
        /* General Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }
    
        /* Navbar Styling (Flush at Top) */
        .search-container {
            display: flex;
            align-items: center;
            background-color: #fff;
            /* Matches your inline style */
            padding: 8px 15px;
            border: 1px solid #ced4da;
            border-radius: 20px;
            width: 300px;
        }
    
        .search-container i {
            margin-right: 10px;
            color: #6c757d;
        }
    
        .search-container input {
            border: none;
            background: none;
            outline: none;
            width: 100%;
            font-size: 14px;
        }
    
        .icons {
            display: flex;
            gap: 20px;
        }
    
        .icon-btn {
            position: relative;
            cursor: pointer;
            font-size: 18px;
            color: #6c757d;
        }
    
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
    
        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    
        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    
        .profile-info {
            display: flex;
            flex-direction: column;
        }
    
        .profile-info span:first-child {
            font-weight: bold;
            font-size: 16px;
        }
    
        .store-name {
            font-size: 12px;
            color: #6c757d;
        }
    
        /* Main Content */
        .main-content {
            padding: 20px;
            margin-top: 70px;
            /* Offset for fixed navbar height */
        }
    
        /* Search and Category Filter */
        .input-group {
            display: flex;
            gap: 10px;
            margin: 20px 0;
        }
    
        .input-group-search {
            border: 1px solid #ced4da;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 14px;
            width: 300px;
        }
    
        .selected {
            border: 1px solid #ced4da;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 14px;
            background-color: #ffffff;
        }
    
        /* Product Cards */
        .product-list .row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            /* Space between cards */
        }
    
        .square-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #ffffff;
            transition: transform 0.2s;
            height: 300px;
            /* Fixed height for uniformity */
            display: flex;
            flex-direction: column;
        }
    
        .square-card:hover {
            transform: translateY(-5px);
        }
    
        .image-wrapper {
            width: 100%;
            height: 180px;
            /* Fixed height for image */
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    
        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Ensures image fills space without distortion */
        }
    
        .card-body {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
    
        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
    
        .price {
            font-size: 14px;
            font-weight: bold;
            color: #28a745;
        }
    
        .buy {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
    
        .buy:hover {
            background-color: #0056b3;
        }
    
        /* Cart Section */
        .cart-section {
            width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            border-radius: 12px;
            padding: 20px;
        }
    
        .cart-section h4 {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }
    
        #cartTable {
            font-size: 14px;
        }
    
        #cartTable th {
            background-color: #2c3e50;
            color: white;
            padding: 10px;
        }
    
        #cartTable td {
            padding: 10px;
            vertical-align: middle;
        }
    
        #grandTotal {
            font-weight: bold;
            color: #28a745;
        }
    
        .btn-success {
            background-color: #28a745;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
    
        .btn-success:hover {
            background-color: #218838;
        }
    
        /* Responsive Design */
        @media (max-width: 992px) {
            .cart-section {
                width: 100%;
                margin-top: 20px;
                margin-left: 0;
            }
    
            .product-list .col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
    
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                padding: 10px 15px;
            }
    
            .search-container,
            .input-group-search {
                width: 100%;
            }
    
            .product-list .col-sm-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
    
        @media (max-width: 576px) {
            .product-list .col-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
    
            .square-card {
                height: 280px;
            }
        }
    </style>
</main>