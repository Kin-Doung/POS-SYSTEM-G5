<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
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
                <span>Eng Ly</span>
                <span class="store-name">Owner Store</span>
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

    <div class="container">
        <form action="/purchase/update?id=<?= $purchase['id'] ?>" method="POST" enctype="multipart/form-data">
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
            <div class="form-group">
                <label for="image" class="form-label">Image:</label>
                <input type="file" id="image" name="image" class="form-control">
                <?php if ($purchase['image']): ?>
                    <img src="<?= htmlspecialchars($purchase['image']) ?>" alt="Current Image" class="mt-2" style="max-width: 150px;">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($purchase['product_name']) ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="price" class="form-label">Price:</label>
                <input type="text" id="price" name="price" value="<?= htmlspecialchars($purchase['price']) ?>" class="form-control">
            </div>

            <button type="submit" class="btn btn-success mt-3">Update</button>
        </form>
    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>