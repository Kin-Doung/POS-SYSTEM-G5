<nav class="navbar">
    <div class="search-container" style="background-color: #fff;">
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
        <link rel="stylesheet" href="../assets/css/settings/list.css">
        <script src="../assets/js/setting.js"></script>
    </div>
</nav>