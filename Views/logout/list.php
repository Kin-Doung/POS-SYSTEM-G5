<?php require_once './views/layouts/side.php' ?>
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
</main>



<!-- log out form -->
<div class="auth-container">
    <div class="logout-card">
        <div class="profile-pic">
            <img src="../../views/assets/images/image.png" alt="">
        </div>
        <div class="eng-ly">Engly shop</div>
        <div class="user-info">
            <p>Username: 
                <span id="username" class="blur-text">Engly</span> 
                <i class="toggle-icon" onclick="toggleVisibility('username', this)">👁️</i>
            </p>
        </div>
        <div class="user-pass">
            <p>Password: 
                <span id="password" class="blur-text">123</span> 
                <i class="toggle-icon" onclick="toggleVisibility('password', this)">👁️</i>
            </p>
        </div>
        <div class="user-email">
            <p>Email: engly@gmail.com</p>
        </div>
        <div class="logout-btn">
            <a href="/">Logout</a>
        </div>
    </div>
</div>


<!-- end of logout form -->

