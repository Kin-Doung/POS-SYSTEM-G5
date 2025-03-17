<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar">
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
        <div class="profile">
            <img src="../../assets/images/image.png" alt="User">
            <div class="profile-info">
                <span>Eng Ly</span>
                <span class="store-name">Owner Store</span>
            </div>
        </div>
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
            <a href="/purchase/create" class="btn bg-info text-light ">Add New</a>

        </form>
    </div>
    <!-- Modal structure -->
    <div class="container-purchase">
        <div class="product-grid" id="productGrid">
            <?php foreach ($purchases as $purchase): ?>

                <div class="card" data-name="<?= $purchase['product_name'] ?>" data-category="<?= $purchase['product_name'] ?>" data-price="<?= $purchase['price'] ?>">
                    <div class="delete_edit">
                        <a href="/purchase/edit?id=<?= $purchase['id'] ?>" class="btn btn-warning">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <!-- Direct delete link with confirmation -->
                        <form action="/purchase/destroy" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $purchase['id'] ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    <img src="uploads/<?= $purchase['image'] ?>" alt="<?= $purchase['product_name'] ?>" />
                    <div class="product-name"><?= $purchase['product_name'] ?></div>
                    <input type="number" class="quantity" id="quantity<?= $purchase['product_name'] ?>" value="0" min="0" />
                    <div class="price">Price: $<?= number_format($purchase['price'], 2) ?></div>
                    <div class="sum">

                        <button class="buy-button" onclick="updateCart(<?= $purchase['price'] ?>, 'quantity<?= $purchase['product_name'] ?>', true)">+</button>
                        <button class="subtract-button" onclick="updateCart(<?= $purchase['price'] ?>, 'quantity<?= $purchase['product_name'] ?>', false)">-</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="detail-section">
            <div class="detail-title">Order Summary</div>
            <table class="order-table">
                <thead>
                    <tr>
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
            <div class="buys">
                <button class="buy-button" style="margin-top: 20px; width: 50%;" onclick="saveToPDF()">Save as PDF</button>
                <button class="buy-button" style="margin-top: 15px; width: 50%; background-color: #27ae60;" onclick="processPurchase()">Place Order</button>
            </div>
        </div>
    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>