<?php require_once './views/layouts/side.php' ?>
<?php require_once './views/layouts/header.php' ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light px-3 d-flex justify-content-between align-items-center">
        <!-- Search Bar -->
        <div class="input-group w-25">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Search...">
        </div>

        <!-- Icons -->
        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-globe fs-5"></i>
            <div class="position-relative">
                <i class="fas fa-bell fs-5"></i>
                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">8</span>
            </div>
        </div>

        <!-- Profile -->
        <div class="d-flex align-items-center gap-2">
            <img src="../../assets/images/image.png" alt="User" class="rounded-circle" width="40" height="40">
            <div class="d-flex flex-column">
                <span class="fw-bold">Eng Ly</span>
                <span class="text-muted small">Owner Store</span>
            </div>
        </div>

        <!-- Mobile Sidebar Toggle -->
        <a href="javascript:;" class="d-xl-none text-body" id="iconNavbarSidenav">
            <i class="fas fa-bars fs-5"></i>
        </a>
    </nav>

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Edit Admin Settings</h4>
            </div>
            <div class="card-body">
                <form action="/settings/update/<?= $admin['id'] ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $admin['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Username:</label>
                        <input type="text" value="<?= htmlspecialchars($admin['username']) ?>" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email:</label>
                        <input type="email" value="<?= htmlspecialchars($admin['email']) ?>" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password <small class="text-muted">(Leave blank to keep current password)</small>:</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter new password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Store Name:</label>
                        <input type="text" value="<?= htmlspecialchars($admin['store_name']) ?>" name="store_name" class="form-control" required>
                    </div>

                    <div class="mb-3 text-center">
                        <label class="form-label fw-bold d-block">Store Logo:</label>
                        <img src="<?= $admin['store_logo'] ?>" alt="Current Logo" class="img-thumbnail mb-2" style="width: 120px; height: auto;">
                        <input type="file" name="store_logo" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Language:</label>
                        <select name="language" class="form-select" required>
                            <option value="en" <?= ($admin['language'] == 'en') ? 'selected' : '' ?>>English</option>
                            <option value="km" <?= ($admin['language'] == 'km') ? 'selected' : '' ?>>Khmer</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </form>
            </div>
        </div>
    </div>











    <?php require_once 'views/layouts/footer.php' ?>
</main>