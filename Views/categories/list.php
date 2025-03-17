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
    <div class="container mt-3">
        <a href="/category/create" style="width: 150px;" class="btn btn-primary ">Add New</a>
        <table class="table table-dark bg-light">

            <thead>
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
                        <td><?= $category['name'] ?></td>
                        <td>
                            <a href="/category/edit?id=<?= $category['id'] ?>" class="btn btn-warning">Edit</a> |
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#category<?= $category['id'] ?>">
                                delete
                            </button>

                            <!-- Modal -->
                            <?php require_once './views/categories/delete.php'; ?>

                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</main>