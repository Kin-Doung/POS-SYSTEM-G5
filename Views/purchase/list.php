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
        <div class="profile">
            <img src="../../views/assets/images/image.png" alt="User">
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

    <div class="input-group">
        <input type="text" id="searchInput" class="form-controlls input-group-search" placeholder="Search...">
        <select id="categorySelect" class="ms-2 selected">
            <option value="">Select Category</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option disabled>No Categories Found</option>
            <?php endif; ?>
        </select>
    </div>
    <!-- End Navbar -->
    <div class="container">
        <div class="mt-5">
            <a href="/purchase/create" class=" create-ct" style="margin-top: 30px; width : 100px;">
                <i class="bi-plus-lg"></i> Add New Cateogries
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>image</th>
                        <th>Name</th>
                        <th>price</th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchase as $index => $purchase): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $purchase['image'] ?></td>
                            <td><?= $purchase['product_name'] ?></td>
                            <td><?= $purchase['price'] ?></td>
                            <td class="text-center text-nowrap">
                                <a href="/purchase/edit?id=<?= $purchase['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#purchase<?= $purchase['id'] ?>">
                                    <i class="bi bi-trash"></i> Delete
                                </button>

                                <!-- Modal -->
                                <?php require_once './views/purchase/delete.php'; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

</main>