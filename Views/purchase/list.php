<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background-color: #fff; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
    <!-- Search Bar -->
    <div class="search-container" style="display: flex; align-items: center; max-width: 600px; width: 100%; border-radius: 25px; border: 2px solid #ccc; padding: 5px 15px; background-color: #f9f9f9;">
        <i class="fas fa-search" style="color: #aaa; font-size: 16px; margin-right: 10px;"></i>
        <input type="text" placeholder="Search..." class="search-bar" 
               style="border: none; outline: none; flex: 1; padding: 5px 10px; font-size: 14px; border-radius: 20px; background-color: transparent; color: #333;">
    </div>

    <!-- Icons -->
    <div class="icons" style="display: flex; align-items: center; gap: 20px;">
        <i class="fas fa-globe icon-btn" style="font-size: 18px; cursor: pointer;"></i>
        <div class="icon-btn" id="notification-icon" style="position: relative; cursor: pointer;">
            <i class="fas fa-bell" style="font-size: 18px;"></i>
            <span class="notification-badge" id="notification-count" style="position: absolute; top: -5px; right: -5px; background-color: red; color: white; font-size: 10px; width: 18px; height: 18px; border-radius: 50%; text-align: center; line-height: 18px;">8</span>
        </div>
    </div>

    <!-- Profile -->
    <div class="profile" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
        <img src="../../assets/images/image.png" alt="User" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
        <div class="profile-info" style="display: flex; flex-direction: column; align-items: flex-start;">
            <span style="font-weight: bold; font-size: 14px;">Engly</span>
            <span class="store-name" style="font-size: 12px; color: #888;">Engly Store</span>
        </div>
    </div>

    <!-- Sidenav toggle button for smaller screens -->
    <li class="nav-item d-xl-none ps-3 d-flex align-items-center" style="display: none;">
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
            <a href="/purchase/create" class="btn bg-info text-light ">Add New</a>
        </form>


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

                    <!-- Button to open the modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal<?= $purchase['id'] ?>">
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

                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>

                                <!-- Delete Form -->
                                <form action="/purchase/destroy" method="POST" style="margin-top: 10px;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($purchase['id']) ?>">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Order Summary Section -->
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

            <div class="buys">
                <button class="buy-button" style="margin-top: 20px; width: 50%;" onclick="saveToPDF()">Save as PDF</button>
                <button class="buy-button" style="margin-top: 15px; width: 50%; background-color: #27ae60;" onclick="processPurchase()">Restock</button>
            </div>
        </div>
    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>
