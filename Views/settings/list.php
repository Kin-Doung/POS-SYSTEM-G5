<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php' ?>
<?php require_once './Databases/database.php' ?>
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
                <!-- Modal structure -->
                <div class="container mt-5 ml-5">
                    <div class="card">
                        <h2>Personal Account</h2>
                        <div class="account-details">
                            <?php if (!empty($admins)) : ?>
                                <?php foreach ($admins as $admin) : ?>
                                    <div class="account-row">
                                        <div class="left">
                                            <div class="aa">
                                                <h6><strong>Profile:</strong></h6>
                                                <div class="image">
                                                    <?php if (!empty($admin['store_logo'])) : ?>
                                                        <img src="data:image/jpeg;base64,<?= base64_encode($admin['store_logo']) ?>"
                                                            style="width: 200px; height: 200px; object-fit: cover; border-radius: 50%;">
                                                    <?php else: ?>
                                                        <span class="no-logo">No Logo</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="aa-1">
                                                <h6><strong>Language:</strong></h6>
                                                <div class="a-1">
                                                    <p><?= htmlspecialchars($admin['language']) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <div class="bb">
                                                <h6><strong>Username:</strong></h6>
                                                <div class="bb-1">
                                                    <p><?= htmlspecialchars($admin['username']) ?></p>
                                                </div>
                                            </div>
                                            <div class="bb">
                                                <h6><strong>Store Name:</strong></h6>
                                                <div class="bb-2">
                                                    <p><?= htmlspecialchars($admin['store_name']) ?></p>
                                                </div>
                                            </div>

                                            <div class="bb">
                                                <h6><strong>Password:</strong></h6>
                                                <div class="bb-3">
                                                    <p><?= substr($admin['password'], 0, 2) . '****' ?></p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="buttom">
                                        <h6><strong>Email:</strong></h6>
                                        <div class="bt-1">
                                            <p><?= htmlspecialchars($admin['email']) ?></p>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="no-admin">
                                    <p class="text-muted">No admin users found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="edit">
                            <a href="settings/edit?id=<?= $admin['id'] ?>" class="edit-button">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
</main>
=======
    <script src="../../views/assets/js/demo/chart-area-demo.js"></script>
>>>>>>> stock/tracking
