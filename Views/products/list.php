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
    <input type="text" id="searchInput" class="form-controlls input-group-search" placeholder="Search...">
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

    <style>
        .navbar {
            width: 100%;
            padding: 15px 20px;
        }

        /* Search and Category Filter */
        .input-group {
            display: flex;
            padding: 15px 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            gap: 10px;
            width: 100%;
        }

        .input-group-search {
            flex: 1 1 200px;
            padding: 10px 15px;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .input-group-search:focus {
            border-color: #1a3c34;
        }

        .selected {
            flex: 1 1 150px;
            padding: 10px;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            font-size: 14px;
            background-color: #ffffff;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .selected:focus {
            border-color: #1a3c34;
            outline: none;
        }

        /* Container and Product Cards */
        .container {
            padding: 0 20px;
            max-width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .product-list {
            flex-grow: 1;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .col-6,
        .col-sm-4,
        .col-md-3 {
            padding: 10px;
            flex: 0 0 50%;
            max-width: 50%;
        }

        @media (min-width: 576px) {
            .col-sm-4 {
                flex: 0 0 33.333%;
                max-width: 33.333%;
            }
        }

        @media (min-width: 768px) {
            .col-md-3 {
                flex: 0 0 25%;
                max-width: 25%;
            }
        }

        .square-card {
            border: none;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            background-color: #ffffff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .square-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .image-wrapper {

            height: 150px;
            overflow: hidden;
            background-color: #f5f6f5;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
            font-weight: 500;
            color: #1a3c34;
            margin-bottom: 5px;
        }

        .price {
            font-size: 14px;
            color: #dc3545;
            font-weight: 600;
        }

        .buy {
            background-color: #1a3c34;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .buy:hover {
            background-color: #152e2a;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Cart Section */
        .cart-section {
            width: 100%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 0 20px 20px;
        }

        @media (min-width: 992px) {
            .container {
                flex-direction: row;
            }

            .cart-section {
                margin: 0 0 0 20px;
                position: sticky;
                top: 20px;
                height: fit-content;
            }
        }

        .cart-section h4 {
            color: #1a3c34;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .cart-section .table {
            margin-bottom: 20px;
        }

        .table-dark {
            background-color: #1a3c34;
            color: white;
        }

        .table-dark th {
            border: none;
            font-weight: 500;
        }

        .table td {
            vertical-align: middle;
            padding: 10px;
            font-size: 14px;
        }

        .cart-section h5 {
            font-size: 18px;
            color: #333;
            text-align: right;
        }

        #grandTotal {
            color: #dc3545;
            font-weight: 600;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Responsive Adjustments */
        @media (max-width: 575px) {
            .input-group {
                padding: 10px;
                margin: 10px;
            }

            .input-group-search,
            .selected {
                width: 100%;
                flex: 1 1 100%;
            }

            .card-title {
                font-size: 14px;
            }

            .price,
            .buy {
                font-size: 12px;
            }

            .image-wrapper {
                height: 120px;
            }
        }
    </style>
    <?php require_once 'views/layouts/footer.php'; ?>
</main>