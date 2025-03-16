<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="/" target="_blank">
            <img src="../assets/img/logos/logo.png" class="navbar-brand-img h-100" alt="main_logo">
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div>
        <ul class="navbar-nav">
            <!-- nav dashboard -->
            <li class="nav-item">
                <a class="nav-link text-white active bg-gradient-primary" href="/">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <!-- nav inventory -->
            <li class="nav-item">
                <a class="nav-link text-white " href="/inventory">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">table_view</i>
                    </div>
                    <span class="nav-link-text ms-1">Inventory</span>
                </a>
            </li>
            <!-- nav product -->
            <li class="nav-item">
                <a class="nav-link text-white " href="/products">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt_long</i>
                    </div>
                    <span class="nav-link-text ms-1">Products</span>
                </a>
            </li>
            <!-- nav purchase order -->
            <li class="nav-item">
                <a class="nav-link text-white " href="/purchase">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">view_in_ar</i>
                    </div>
                    <span class="nav-link-text ms-1">Purchase Oders</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="../pages/rtl.html">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">format_textdirection_r_to_l</i>
                    </div>
                    <span class="nav-link-text ms-1">Invoice</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="/notifications">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">notifications</i>
                    </div>
                    <span class="nav-link-text ms-1">Notifications</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#settingsMenu" role="button" aria-expanded="false" aria-controls="settingsMenu">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
                        <i class="fas fa-cog"></i> Setting
                    </h6>
                </a>
            </li>

            <div class="collapse ps-4" id="settingsMenu">
                <li class="nav-item">
                    <a class="nav-link text-white" href="../pages/profile.html">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <span class="nav-link-text ms-1">Account</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="../pages/sign-in.html">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">language</i>
                        </div>
                        <span class="nav-link-text ms-1">Language</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="../pages/sign-in.html">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">logout</i>
                        </div>
                        <span class="nav-link-text ms-1">Log Out</span>
                    </a>
                </li>
            </div>


        </ul>
    </div>

</aside>
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
    <?php require_once 'Views/layouts/header.php'; ?>
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
    <div class="container">
        <div class="product-grid" id="productGrid">
            <?php
            foreach ($purchases as $purchase): ?>
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
                    <button class="buy-button" onclick="updateCart(<?= $purchase['price'] ?>, 'quantity<?= $purchase['product_name'] ?>', true)">Add to Cart</button>
                    <button class="subtract-button" onclick="updateCart(<?= $purchase['price'] ?>, 'quantity<?= $purchase['product_name'] ?>', false)">Remove</button>
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
            <button class="buy-button" style="margin-top: 20px; width: 100%;" onclick="saveToPDF()">Save as PDF</button>
            <button class="buy-button" style="margin-top: 15px; width: 100%; background-color: #27ae60;" onclick="processPurchase()">Place Order</button>
        </div>
    </div>



    <?php require_once 'Views/layouts/footer.php'; ?>
</main>