<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>


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
    <?php foreach ($inventory as $key => $product): ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-6 col-sm-4 col-md-3 mb-4">
                    <div class="card square-card">
                        <img src="watch-image-url.jpg" class="card-img-top" alt="Elegant Watch">
                        <div class="card-body">
                            <h6 class="card-title text-center"> <?=$product['name']?></h6>
                            <p class="card-text text-center price"><?=$product['amount'] ?></p>
                            <p class="card-text text-center rating">★★★★☆</p>
                            <!-- <p class="card-text text-center small">Lorem ipsum dolor sit amet.</p> -->
                            <a href="#" class="btn btn-sm btn-primary d-block mx-auto">Buy Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>