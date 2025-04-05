<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';

?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
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

    <div class="container">
        <form action="/category/update?id=<?= $category['id'] ?>" method="POST">
            <div class="form-group">
                <label for="" class="form-label">Name:</label>
                <input type="text" value=" <?= $category['name'] ?>" name="name" class="form-controll">
            </div>
            <button type="submit" class="btn btn-success mt-3">Update</button>
        </form>
    </div>
    <?php require_once 'views/layouts/footer.php' ?>
</main>