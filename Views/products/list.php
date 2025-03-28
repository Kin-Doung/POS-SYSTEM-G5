<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="search-container">
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
        <input type="text" id="searchInput" class="form-controlls input-group-search" placeholder="Search...">
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
<<<<<<< HEAD
    <?php if (isset($inventory) && is_array($inventory) && !empty($inventory)): ?>
        <div class="container mt-5">
=======

    <!-- Product Card Section -->
    <div class="container mt-5 d-flex">
        <div class="product-list flex-grow-1">
>>>>>>> 7a7190213c2d10e73f3045b2398271c3c62c5782
            <div class="row">
                <?php foreach ($inventory as $key => $product): ?>
                    <div class="col-6 col-sm-4 col-md-3 mb-4">
                        <div class="card square-card">
                            <img src="watch-image-url.jpg" class="card-img-top" alt="Elegant Watch">
                            <div class="card-body">
                                <h6 class="card-title text-center"><?= htmlspecialchars($product['name']) ?></h6>
                                <p class="card-text text-center price"><?= htmlspecialchars($product['amount']) ?></p>
                                <p class="card-text text-center rating">★★★★☆</p>
                                <a href="#" class="btn btn-sm btn-primary d-block mx-auto">Buy Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
<<<<<<< HEAD
    <?php else: ?>
        <p class="text-center mt-5">No products found.</p>
    <?php endif; ?>


=======

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
>>>>>>> 7a7190213c2d10e73f3045b2398271c3c62c5782
    <?php require_once 'views/layouts/footer.php'; ?>
</main>

