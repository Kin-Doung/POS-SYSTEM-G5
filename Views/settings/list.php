<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
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
        <div class="profile">
            <img src="../../assets/images/image.png" alt="User">
            <div class="profile-info">
                <span>Eng Ly</span>
                <span class="store-name">Owner Store</span>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <?php require_once 'views/layouts/header.php'; ?>

    <!-- Modal structure -->
    <div class="container mt-4">
        <a href="/settings/create" style="width: 150px;" class="btn btn-primary ">Add New</a>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Admin Setting</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Store Name</th>
                                <th>Store Logo</th>
                                <th>Language</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($admins)) : ?>
                                <?php foreach ($admins as $admin) : ?>
                                    <tr>
                                        <!-- <td><?= $admin['id'] ?></td> -->
                                        <td><?= htmlspecialchars($admin['username']) ?></td>
                                        <td><?= htmlspecialchars($admin['email']) ?></td>
                                        <td><?= substr($admin['password'], 0, 2) . '****' ?></td>
                                        <td><?= htmlspecialchars($admin['store_name']) ?></td>
                                        <td>
                                            <?php if (!empty($admin['store_logo'])) : ?>
                                                <img src="data:image/jpeg;base64,<?= base64_encode($admin['store_logo']) ?>"
                                                    alt="Profile Image"
                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                            <?php else: ?>
                                                No Logo
                                            <?php endif; ?>
                                        </td>


                                        <td><?= htmlspecialchars($admin['language']) ?></td>
                                        <td>
                                            <a href="settings/edit?id=<?= $admin['id'] ?>" class="btn btn-warning">Edit</a>
                                            <!-- <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#user<?= $user['id'] ?>">
                                                delete
                                            </button> -->
                                            <!-- Modal -->
                                            <?php require 'delete.php' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="text-center">No admin users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="text-start">
                <a href="/logout" class="btn btn-warning">Logout</a>
            </div>

        </div>
    </div>



    <?php require_once 'views/layouts/footer.php'; ?>
</main>