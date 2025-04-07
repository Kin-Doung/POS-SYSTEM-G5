<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" style="margin-left: 250px;">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <nav class="navbar ml-4 mb-5">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                    <div class="icons">
                        <i class="fas fa-globe icon-btn"></i>
                        <div class="icon-btn" id="notification-icon">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" id="notification-count">8</span>
                        </div>
                    </div>
                    <div class="profile" id="profile">
                        <img src="../../views/assets/images/image.png" alt="User">
                        <div class="profile-info">
                            <span id="profile-name">Eng Ly</span>
                            <span class="store-name" id="store-name">Owner Store</span>
                        </div>
                        <ul class="menu" id="menu">
                            <li><a href="/settings" class="item">Account</a></li>
                            <li><a href="/settings" class="item">Setting</a></li>
                            <li><a href="/logout" class="item">Logout</a></li>
                        </ul>
                        <link rel="stylesheet" href="../../views/assets/css/settings/list.css">
                        <script src="../../views/assets/js/setting.js"></script>
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
                        <div class="row" id="product-list">
                            <?php foreach ($products as $product): ?>
                                <div class="col-6 col-sm-4 col-md-3 mb-4 product-item" data-category-id="<?= htmlspecialchars($product['category_id']) ?>">
                                    <div class="card square-card">
                                        <div class="image-wrapper">
                                            <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title text-center"><?= htmlspecialchars($product['name']) ?></h6>
                                            <p class="card-text text-center price"><?= htmlspecialchars($product['price']) ?> $</p>

                                            <!-- Quantity and Buy Button -->
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
                            <button class="btn btn-success" onclick="replaceCartInDatabase()">submit</button>
                        </div>

                    </div>
                </div>

                <?php require_once 'views/layouts/footer.php'; ?>

            </div>
        </div>
    </div>
    <script src="../../views/assets/js/demo/chart-area-demo.js"></script>