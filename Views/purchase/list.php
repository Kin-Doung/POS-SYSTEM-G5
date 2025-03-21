<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
     <!-- Navbar -->
     <nav class="navbar">
        <!-- Search Bar -->
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search..." class="search-bar">
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
                <span>Engly</span>
                <span class="store-name">Engly Store</span>
            </div>
        </div>

        <!-- Sidenav toggle button for smaller screens -->
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

    <!-- Search Section -->
    <div class="search-section">
        <form action="" method="POST">
            <input type="text" class="search-input" id="searchInput" name="searchInput" placeholder="Search for products..." onkeyup="filterProducts()" />

            <select class="category-select" id="categorySelect" name="categorySelect" onchange="filterProducts()">
                <option value="">Select Category</option>
                <option value="all">All</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['name']) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select class="price-select" id="priceSelect" name="priceSelect" onchange="filterProducts()">
                <option value="">Select Price Range</option>
                <option value="0">Up to $10</option>
                <option value="15">Up to $15</option>
                <option value="20">Up to $20</option>
                <option value="25">Up to $25</option>
                <option value="30">Up to $30</option>
            </select>
            <a href="/purchase/create" class="btn bg-info text-light" style="margin-top: -10px;">Add New</a>
        </form>
    </div>
    

    <!-- Modal structure -->
    <div class="container-purchase">
        <div class="product-grid" id="productGrid">
            <?php foreach ($purchases as $purchase): ?>
                <div class="card"
                    data-name="<?= htmlspecialchars($purchase['product_name']) ?>"
                    data-category="<?= htmlspecialchars($purchase['category_id']) ?>"
                    data-price="<?= htmlspecialchars($purchase['price']) ?>">
                    <img src="<?= '/uploads/' . htmlspecialchars($purchase['image']) ?>" alt="Product Image">

                    <div class="product-name"><?= htmlspecialchars($purchase['product_name']) ?></div>

                    <input type="number" class="quantity" id="quantity<?= $purchase['id'] ?>" value="0" min="0" />

                    <div class="price">Price: $<?= number_format($purchase['price'], 2) ?></div>

                    <div class="sum">
                        <button class="buy-button" onclick="updateCart(<?= $purchase['price'] ?>, 'quantity<?= $purchase['id'] ?>', true)">+</button>
                        <button class="subtract-button" onclick="updateCart(<?= $purchase['price'] ?>, 'quantity<?= $purchase['id'] ?>', false)">-</button>
                    </div>
                    <button type="button" class=" btn-primary move-button" data-bs-toggle="modal" data-bs-target="#modal<?= $purchase['id'] ?>" >
    View detail
</button>

                </div>

                <!-- Modal for each product -->
                <div class="modal fade" id="modal<?= $purchase['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $purchase['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel<?= $purchase['id'] ?>">Product Detail</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form action="/purchase/update?id=<?= htmlspecialchars($purchase['id']) ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($purchase['image'] ?? '') ?>">

                                    <div class="mb-3">
                                        <label for="product_name<?= $purchase['id'] ?>" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="product_name" id="product_name<?= $purchase['id'] ?>" value="<?= htmlspecialchars($purchase['product_name'] ?? '') ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image<?= $purchase['id'] ?>" class="form-label">Profile Image</label>
                                        <input type="file" class="form-control" name="image" id="image<?= $purchase['id'] ?>" accept="image/*">
                                        <?php if (!empty($purchase['image'])) : ?>
                                            <img src="/uploads/<?= htmlspecialchars($purchase['image']) ?>" alt="Current Image" style="width: 80px; height: 80px; border-radius: 50%; margin-top: 10px;">
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="price<?= $purchase['id'] ?>" class="form-label">Price</label>
                                        <input type="number" class="form-control" name="price" id="price<?= $purchase['id'] ?>" value="<?= htmlspecialchars($purchase['price'] ?? '') ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="category_id<?= $purchase['id'] ?>" class="form-label">Category</label>
                                        <select class="form-control" name="category_id" id="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php if (!empty($categories)): ?>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= htmlspecialchars($category['id']); ?>" <?= ($purchase['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="">No categories available</option>
                                            <?php endif; ?>
                                        </select>

                                    </div>

                                    <button type="submit" class="btn btn-primary update-card" style="width: 40%;">Update</button>
                                   
                                </form>
        
                            </div>
                            <div class="modal-footer">
                                <form action="/purchase/destroy" method="POST" style="margin-top: 10px;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($purchase['id']) ?>">
                                    <button type="submit" class="btn btn-danger delete-card" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                                </form>
                            </div>
                           
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

<!-- Order Summary Button -->
<button class="total-button" onclick="toggleOrderSummary()">Total</button>

<!-- Order Summary Section -->
<div class="detail-section" id="orderSummary">
    <!-- Close button (X) -->
    <button class="close-button" onclick="closeOrderSummary()">X</button>
    
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

    <div class="buys">
    <button class="buy-button" onclick="saveToPDF()">Save as PDF</button>
    <button class="buy-button" onclick="processPurchase()">Restock</button>
</div>

</div>


    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>

<script>
// Function to toggle the visibility of the order summary card
let isDragging = false;
let startX, startY;

function toggleOrderSummary() {
    const orderSummary = document.getElementById("orderSummary");

    if (orderSummary.style.display === "none" || orderSummary.style.display === "") {
        // Make the card visible
        orderSummary.style.display = "block";

        // Center the card if it's the first time opening
        if (!orderSummary.dataset.moved) {
            centerOrderSummary(orderSummary);
        }

        // Make the card draggable
        makeDraggable(orderSummary);
    } else {
        orderSummary.style.display = "none";
    }
}

function closeOrderSummary() {
    document.getElementById("orderSummary").style.display = "none";
}

// Function to center the order summary card on the screen
function centerOrderSummary(orderSummary) {
    const screenWidth = window.innerWidth;
    const screenHeight = window.innerHeight;

    const cardWidth = orderSummary.offsetWidth;
    const cardHeight = orderSummary.offsetHeight;

    const left = (screenWidth - cardWidth) / 2;
    const top = (screenHeight - cardHeight) / 2;

    orderSummary.style.left = `${left}px`;
    orderSummary.style.top = `${top}px`;

    // Mark it as moved to prevent resetting its position every time
    orderSummary.dataset.moved = "true";
}

// Function to make the card draggable
function makeDraggable(orderSummary) {
    orderSummary.addEventListener('mousedown', function (e) {
        if (e.target !== orderSummary) return;

        isDragging = true;
        startX = e.clientX - orderSummary.offsetLeft;
        startY = e.clientY - orderSummary.offsetTop;

        orderSummary.style.cursor = 'move';
    });

    document.addEventListener('mousemove', function (e) {
        if (!isDragging) return;

        const x = e.clientX - startX;
        const y = e.clientY - startY;

        orderSummary.style.transition = 'none';
        orderSummary.style.left = `${x}px`;
        orderSummary.style.top = `${y}px`;
    });

    document.addEventListener('mouseup', function () {
        isDragging = false;
        orderSummary.style.cursor = 'default';

        setTimeout(() => {
            orderSummary.style.transition = 'top 0.2s ease, left 0.2s ease';
        }, 100);
    });
}

</script>