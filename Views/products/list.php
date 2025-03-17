<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar">
        <!-- Search Bar -->
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>

        <!-- Icons -->
        <div class="icons">
            <i class="fas fa-globe icon-btn"></i>
            <div class="icon-btn" id="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notification-count">8</span>
            </div>
        </div>

        <!-- Profile -->
        <div class="profile">
            <img src="../../assets/images/image.png" alt="User">
            <div class="profile-info">
                <span>Jimmy Sullivan</span>
                <span class="store-name">Odama Store</span>
            </div>
        </div>
        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
            </a>
        </li>
    </nav>
    <!-- End Navbar -->
    <?php require_once 'views/layouts/header.php'; ?>


    <div class="search-section">
        <form action="" method="POST">
            <input type="text" class="search-input" id="searchInput" name="searchInput" placeholder="Search for products..." onkeyup="filterProducts()" />
            <select class="product-select" id="productSelect" name="productSelect" onchange="filterProducts()">
                <option value="">Select Product</option>
                <option value="Adapter">Adapter</option>
                <option value="Cake Mixer">Cake Mixer</option>
                <option value="Cocktail Machine">Cocktail Machine</option>
                <option value="Electric Cooking Pot">Electric Cooking Pot</option>
            </select>
            <select class="category-select" id="categorySelect" name="categorySelect" onchange="filterProducts()">
                <option value="">Select Category</option>
                <option value="all">All</option>
                <option value="kitchen">Kitchen</option>
                <option value="appliances">Appliances</option>
            </select>
            <select class="price-select" id="priceSelect" name="priceSelect" onchange="filterProducts()">
                <option value="">Select Price Range</option>
                <option value="0">Up to $10</option>
                <option value="15">Up to $15</option>
                <option value="20">Up to $20</option>
                <option value="25">Up to $25</option>
                <option value="30">Up to $30</option>
            </select>
        </form>
    </div>
    <div class="container-product">
        <div class="container">
            <div class="container mt-3">
                <table class="table-dark, bg-light">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!isset($purchases) || !is_array($purchases)): ?>
                            <tr>
                                <td colspan="5" class="text-danger fw-bold">No products available.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($purchases as $purchase): ?>
                                <tr>
                                    <td class="text-primary fw-bold">
                                        <?= isset($purchase['product_name']) ? htmlspecialchars($purchase['product_name']) : 'N/A' ?>
                                    </td>
                                    <td>
                                        <?php
                                        $imagePath = "uploads/" . htmlspecialchars($purchase['product_image']);
                                        if (!file_exists($imagePath)) {
                                            echo "<span class='text-danger'>Image not found</span>";
                                        }
                                        ?>
                                        <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($purchase['product_name'] ?? 'No Name') ?>"
                                            onerror="this.onerror=null; this.src='/uploads/default.png';"
                                            class="img-thumbnail rounded shadow-sm" width="60" height="60">
                                    </td>
                                    <td class="text-success fw-bold">$<?= isset($purchase['price']) ? number_format($purchase['price'], 2) : '0.00' ?></td>
                                    <td>
                                        <input type="number" class="form-control text-center"
                                            id="quantity<?= isset($purchase['product_name']) ? htmlspecialchars($purchase['product_name']) : 'N/A' ?>"
                                            value="0" min="0" style="width: 80px;">
                                    </td>
                                    <td>
                                        <a href="/products/edit?id=<?= isset($purchase['id']) ? $purchase['id'] : '#' ?>" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <button class="btn btn-success btn-sm"
                                            onclick="updateCart(<?= isset($purchase['price']) ? $purchase['price'] : '0' ?>, 'quantity<?= isset($purchase['product_name']) ? htmlspecialchars($purchase['product_name']) : 'N/A' ?>', true)">
                                            <i class="fa-solid fa-cart-plus"></i> Add
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="updateCart(<?= isset($purchase['price']) ? $purchase['price'] : '0' ?>, 'quantity<?= isset($purchase['product_name']) ? htmlspecialchars($purchase['product_name']) : 'N/A' ?>', false)">
                                            <i class="fa-solid fa-minus"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="detail-section">
            <div class="detail-title">Order Summary</div>
            <table class="order-table">
                <thead>
                    <tr class="thead">
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="details"></tbody>
            </table>
            <div class="total" id="totalPrice">Cart Total: $0.00</div>
            <canvas id="qrCode" style="margin-top: 20px;"></canvas>
            <button class="buy-button" style="margin-top: 20px; width: 100%;" onclick="saveToPDF()">Save as PDF</button>
            <button class="buy-button" style="margin-top: 15px; width: 100%; background-color: #27ae60;" onclick="processPurchase()">Place Order</button>
        </div>
    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>