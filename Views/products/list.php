<?php require_once './views/layouts/side.php' ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <?php require_once './views/layouts/header.php'; ?>
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
        </form>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">Item List</h2>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchase as $item): ?>
                    <tr>
                        <td>
                            <img src="<?= '/uploads/' . htmlspecialchars($item['product_image']) ?>" alt="Product Image" class="img" width="50">
                        </td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td class="price">
                            <?= $item['product_price'] == 0 ? 'Free' : '$' . number_format($item['product_price'], 2) ?>
                        </td>
                        <td>
                            <input type="number" min="0" value="0" class="form-control quantity" style="width: 80px;">
                        </td>
                        <td class="text-center">
                            <button type="submit" class="btn btn-success btn-sm buy-btn">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Purchase Modal -->
        <div id="purchase-modal" class="purchase-card" style="display: none;">
            <div class="card" style="width: 70%; margin: auto;">
                <div class="card-body">
                    <h5 class="card-title">Purchase Summary</h5>
                    <!-- Product Details Table -->
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="product-details-list">
                            <!-- Product details will be populated here -->
                        </tbody>
                    </table>
                    <hr>
                    <p id="overall-total" class="text-right"><strong>Total Amount: $0.00</strong></p>
                    <div class="text-right">
                        <button class="btn btn-primary" id="confirm-purchase">Confirm Purchase</button>
                        <button class="btn btn-secondary" id="close-modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mt-4">
            <!-- Show Details Button -->
            <button class="btn btn-info" id="show-details-btn">Show Details</button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Item Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="modalBodyContent">
                            <!-- Dynamic content will be injected here -->
                        </tbody>
                    </table>
                    <div class="text-right">
                        <button class="btn btn-primary" id="savePdfBtn">Save PDF</button>
                        <button class="btn btn-warning" id="restockBtn">Restock</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>