<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php' ?>
<?php require_once './Databases/database.php' ?>


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
                <div class="container mt-5">
                    <div class="account-card">
                        <div class="account-card-header">
                            <h2>Personal Account</h2>
                        </div>
                        <div class="account-details">
                            <?php if (!empty($admins)) : ?>
                                <?php foreach ($admins as $admin) : ?>
                                    <div class="account-row">
                                        <div class="account-left">
                                            <div class="account-field">
                                                <h6><strong>Profile:</strong></h6>
                                                <div>
                                                    <?php if (!empty($admin['store_logo'])) : ?>
                                                        <img src="data:image/jpeg;base64,<?= base64_encode($admin['store_logo']) ?>"
                                                            class="profile-img" alt="Store Logo">
                                                    <?php else: ?>
                                                        <span class="no-logo">No Logo</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="account-field">
                                                <h6><strong>Language:</strong></h6>
                                                <p><?= htmlspecialchars($admin['language']) ?></p>
                                            </div>
                                        </div>
                                        <div class="account-right">
                                            <div class="account-field">
                                                <h6><strong>Username:</strong></h6>
                                                <p><?= htmlspecialchars($admin['username']) ?></p>
                                            </div>
                                            <div class="account-field">
                                                <h6><strong>Store Name:</strong></h6>
                                                <p><?= htmlspecialchars($admin['store_name']) ?></p>
                                            </div>
                                            <div class="account-field">
                                                <h6><strong>Password:</strong></h6>
                                                <p>********</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="account-field">
                                        <h6><strong>Email:</strong></h6>
                                        <p><?= htmlspecialchars($admin['email']) ?></p>
                                    </div>
                                    <div class="edit text-end mt-3">
                                        <a href="settings/edit?id=<?= $admin['id'] ?>" class="edit-button">Edit</a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="no-admin">
                                    <p class="text-muted">No admin users found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <style>
                    .account-card {
                        max-width: 800px;
                        margin: 0 auto;
                        border: none;
                        border-radius: 15px;
                        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                        background: #ffffff;
                    }

                    .account-card-header {
                        background: linear-gradient(135deg, #007bff, #0056b3);
                        color: white;
                        padding: 20px;
                        text-align: center;
                        border-radius: 15px 15px 0 0;
                    }

                    .account-details {
                        padding: 30px;
                    }

                    .account-row {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 20px;
                        align-items: flex-start;
                    }

                    .account-left,
                    .account-right {
                        flex: 1;
                        min-width: 250px;
                    }

                    .profile-img {
                        width: 150px;
                        height: 150px;
                        object-fit: cover;
                        border-radius: 50%;
                        border: 3px solid #e9ecef;
                        transition: transform 0.3s ease;
                    }

                    .profile-img:hover {
                        transform: scale(1.05);
                    }

                    .no-logo {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 150px;
                        height: 150px;
                        background: #e9ecef;
                        border-radius: 50%;
                        color: #6c757d;
                        font-size: 16px;
                        text-align: center;
                    }

                    .account-field {
                        margin-bottom: 20px;
                    }

                    .account-field h6 {
                        margin-bottom: 5px;
                        color: #343a40;
                        font-weight: 600;
                    }

                    .account-field p {
                        margin: 0;
                        color: #495057;
                        background: #f8f9fa;
                        padding: 10px;
                        border-radius: 5px;
                        font-size: 16px;
                    }

                    .edit-button {
                        display: inline-block;
                        padding: 10px 20px;
                        background: #28a745;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                        transition: background 0.3s ease;
                    }

                    .edit-button:hover {
                        background: #218838;
                    }

                    .no-admin {
                        text-align: center;
                        padding: 20px;
                        color: #6c757d;
                    }

                    @media (max-width: 576px) {
                        .account-row {
                            flex-direction: column;
                        }
                    }
                </style>
            </div>
        </div>
    </div>
    <script src="../../views/assets/js/demo/chart-area-demo.js"></script>

</body>