<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php' ?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" style="margin-left: 250px;">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php require_once "./views/layouts/nav.php" ?>
                <!-- Modal structure -->
                <div class="container mt-5">
                    <div class="account-card">
                        <div class="account-card-header">
                            <h2 data-translate-key="Personal Account">Personal Account</h2>
                        </div>
                        <div class="account-details">
                            <?php if (!empty($admins)) : ?>
                                <?php foreach ($admins as $admin) : ?>
                                    <div class="account-row">
                                        <div class="account-left">
                                            <div class="account-field">
                                                <h6><strong data-translate-key="Profile">Profile:</strong></h6>
                                                <div>
                                                    <?php if (!empty($admin['store_logo'])) : ?>
                                                        <img src="data:image/jpeg;base64,<?= base64_encode($admin['store_logo']) ?>"
                                                             class="profile-img" alt="Store Logo">
                                                    <?php else: ?>
                                                        <span class="no-logo" data-translate-key="No Logo">No Logo</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="account-field">
                                                <h6><strong data-translate-key="Language">Language:</strong></h6>
                                                <p><?= htmlspecialchars($admin['language']) ?></p>
                                            </div>
                                        </div>
                                        <div class="account-right">
                                            <div class="account-field">
                                                <h6><strong data-translate-key="Username">Username:</strong></h6>
                                                <p><?= htmlspecialchars($admin['username']) ?></p>
                                            </div>
                                            <div class="account-field">
                                                <h6><strong data-translate-key="Store Name">Store Name:</strong></h6>
                                                <p><?= htmlspecialchars($admin['store_name']) ?></p>
                                            </div>
                                            <div class="account-field">
                                                <h6><strong data-translate-key="Password">Password:</strong></h6>
                                                <p>********</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="account-field">
                                        <h6><strong data-translate-key="Email">Email:</strong></h6>
                                        <p><?= htmlspecialchars($admin['email']) ?></p>
                                    </div>
                                    <div class="edit text-end mt-3">
                                        <a href="settings/edit?id=<?= $admin['id'] ?>" class="edit-button" 
                                           data-translate-key="Edit" aria-label="Edit account">Edit</a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="no-admin">
                                    <p class="text-muted" data-translate-key="No admin users found">No admin users found.</p>
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

    <script>
        // Ensure translations are applied on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedLang = localStorage.getItem('selectedLanguage') || 'en';
            applyTranslations(savedLang);
        });
    </script>
</body>