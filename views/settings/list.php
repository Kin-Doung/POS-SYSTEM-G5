<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php' ?>
<?php require_once './Databases/database.php' ?>

<style>
    h2 {
        font-family: "Poppins", sans-serif;
        font-size: 25px;
        font-weight: 600;
        letter-spacing: 0.3px;
        margin-top: -7px;
    }
    .account-card-header {
        height: 50px;
    }
</style>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" style="margin-left: 250px;">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
              <?php require_once './views/layouts/nav.php' ?>
                <!-- Modal structure -->
                <div class="container">
                    <div class="account-card">
                        <div class="account-card-header">
                            <h2>Account Setting</h2>
                        </div>
                        <div class="account-details">
                            <?php if (!empty($admins)) : ?>
                                <?php foreach ($admins as $admin) : ?>
                                    <div class="account-row">
                                        <div class="account-left">
                                            <div class="account-field">
                                                <h6><strong>Profile:</strong></h6>
                                                <div class="profile-container">
                                                    <?php if (!empty($admin['store_logo'])) : ?>
                                                        <img src="data:image/jpeg;base64,<?= base64_encode($admin['store_logo']) ?>"
                                                            class="profile-img" alt="Store Logo">
                                                    <?php else: ?>
                                                        <span class="no-logo">No Logo</span>
                                                    <?php endif; ?>
                                                </div>
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
                                            <div class="account-field password-field">
                                                <h6><strong>Password:</strong></h6>
                                                <div class="password-container">
                                                    <!-- Use type="text" to control display; show asterisks by default -->
                                                    <input type="text" class="password-input" value="*********" readonly data-password="engly@123">
                                                    <i class="fas fa-eye-slash toggle-password"></i>
                                                </div>
                                            </div>
                                            <div class="account-field">
                                                <h6><strong>Email:</strong></h6>
                                                <p><?= htmlspecialchars($admin['email']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right mt-3">
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
                        max-width: 100%;
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

                    .profile-container {
                        display: flex;
                        justify-content: center; /* Center horizontally */
                        align-items: center; /* Center vertically */
                        width: 100%; /* Ensure it takes full width of parent */
                        height: 300px; /* Increased to accommodate the larger image */
                    }

                    .profile-img {
                        width: 250px;
                        height: 250px;
                        object-fit: contain;
                        border-radius: 50%;
                        border: 3px solid #e9ecef;
                        transition: transform 0.3s ease;
                        /* Removed margin-top: 200px to prevent overflow */
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

                    .password-field .password-container {
                        position: relative;
                        display: flex;
                        align-items: center;
                    }

                    .password-field .password-input {
                        width: 100%;
                        padding: 10px 30px 10px 10px;
                        background: #f8f9fa;
                        border: none;
                        border-radius: 5px;
                        color: #495057;
                        font-size: 16px;
                        font-family: monospace;
                    }

                    .password-field .toggle-password {
                        position: absolute;
                        right: 10px;
                        cursor: pointer;
                        color: #6c757d;
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
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function () {
                const input = this.previousElementSibling;
                const actualPassword = input.getAttribute('data-password');
                if (input.value === '*********') {
                    input.value = actualPassword;
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                } else {
                    input.value = '*********';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                }
            });
        });
    </script>
    <script src="../../views/assets/js/demo/chart-area-demo.js"></script>
</body> 