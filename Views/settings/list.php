<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php' ?>
<?php require_once './Databases/database.php' ?>
<main class="main-content position-relative max-height-vh-50 h-50 border-radius-lg ">
    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>
    <!-- Modal structure -->

    <div class="container">
        <div class="card">
            <h2>Personal Account</h2>
            <div class="account-details">
                <?php if (!empty($admins)) : ?>
                    <?php foreach ($admins as $admin) : ?>
                        <div class="account-row">
                            <div class="profile-image">
                                <p><strong>Profile:</strong></p>
                                <div class="image">
                                    <?php if (!empty($admin['store_logo'])) : ?>
                                        <img src="data:image/jpeg;base64,<?= base64_encode($admin['store_logo']) ?>"
                                            style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                                    <?php else: ?>
                                        <span class="no-logo">No Logo</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="account">
                                <div class="a-1">
                                    <h6><strong>Username:</strong></h6>
                                    <div class="a"> <p><?= htmlspecialchars($admin['username']) ?></p></div>
                                </div>
                                <div class="a-1">
                                    <h6><strong>Email:</strong></h6>
                                    <div class="a"><p><?= htmlspecialchars($admin['email']) ?></p></div>
                                </div>
                                <div class="a-1">
                                    <h6><strong>Password:</strong></h6>
                                    <div class="a"> <p><?= substr($admin['password'], 0, 2) . '****' ?></p></div>
                                </div>
                                <div class="a-1">
                                    <h6><strong>Store Name:</strong></h6>
                                    <div class="a"><p><?= htmlspecialchars($admin['store_name']) ?></p></div>
                                </div>
                                <div class="a-1">
                                    <h6><strong>Language:</strong></h6>
                                    <div class="a"><p><?= htmlspecialchars($admin['language']) ?></p></div>
                                </div>
                            </div>
                        </div>

                        <a href="settings/edit?id=<?= $admin['id'] ?>" class="custom-btn">Edit</a>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="no-admin">
            <p class="text-muted">No admin users found.</p>
        </div>
    <?php endif; ?>
    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>