<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>



<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->

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
</main>