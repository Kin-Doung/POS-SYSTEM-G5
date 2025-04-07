<?php require_once './views/layouts/side.php' ?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" style="margin-left: 250px;">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <nav class="navbar ml-4 mb-5">
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


            </div>
        </div>
    </div>
    <script src="../../views/assets/js/demo/chart-area-demo.js"></script>



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