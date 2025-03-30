<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-10 h-10 border-radius-lg ">
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
    <!-- End Navbar -->
    <div class="container">
        <div class="mt-5">
            <a href="/category/create" class="create-ct">
                <i class="bi-plus-lg"></i> Add New Categories
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $index => $category): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($category['name']) ?></td>
                            <td class="text-center text-nowrap">
                                <a href="/category/edit?id=<?= $category['id'] ?>" class="btn-sm btn-warning">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                                <button type="button" class="btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#category<?= $category['id'] ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <!-- Modal (Assuming delete.php contains the modal content) -->
                                <?php require_once './views/categories/delete.php'; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>


</main>