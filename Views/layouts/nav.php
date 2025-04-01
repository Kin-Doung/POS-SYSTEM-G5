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
        <!-- <img id="profile-logo" src="data:image/jpeg;base64,<?= base64_encode($admin['store_logo']) ?>" alt="Store Logo" width="100"> -->
        <div class="profile-info">
            <!-- <span id="profile-name"><?= $admin['username'] ?></span> -->
            <span id="profile-name" style="color: darkslategray;">Eng Ly</span>
            <span class="store-name" id="store-name">Owner Store</span>
        </div>
        <ul class="menu" id="menu">
            <li><a href="/settings" class="item"><i class="fas fa-user"></i>  Account</a></li>
            <li><a href="/language" class="item"><i class="fas fa-language"></i>  Language</a></li>
            <li><a href="/logout" class="item"><i class="fas fa-sign-out-alt"></i>  Logout</a></li>
        </ul>

        <link rel="stylesheet" href="../../views/assets/css/settings/list.css">
        <link rel="stylesheet" href="../../views/assets/css/settings/setting.css">
        <script src="../../views/assets/js/setting.js"></script>
    </div>

</nav>
