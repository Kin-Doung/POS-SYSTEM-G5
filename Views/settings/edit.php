<?php require_once './views/layouts/side.php' ?>
<?php require_once './views/layouts/header.php' ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
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

    <div class="container mt-4" style="max-width: 1000px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h6 class="mb-0">Edit Admin Settings</h6>
            </div>
            <div class="card-body">
                <form action="/settings/update/<?= $admin['id'] ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $admin['id'] ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" value="<?= htmlspecialchars($admin['username']) ?>" name="username" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" value="<?= htmlspecialchars($admin['email']) ?>" name="email" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password <small class="text-muted">(optional)</small></label>
                            <input type="password" name="password" class="form-control" placeholder="New password">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Store Name</label>
                            <input type="text" value="<?= htmlspecialchars($admin['store_name']) ?>" name="store_name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Store Logo</label>
                            <div class="text-center">
                                <img src="<?= $admin['store_logo'] ?>" class="img-thumbnail mb-2" style="width: 80px; height: auto; border-radius: 10px;">
                            </div>
                            <input type="file" name="store_logo" class="form-control" accept="image/*">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Language</label>
                            <select name="language" class="form-select" required>
                                <option value="en" <?= ($admin['language'] == 'en') ? 'selected' : '' ?>>English</option>
                                <option value="km" <?= ($admin['language'] == 'km') ? 'selected' : '' ?>>Khmer</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-primary px-4 me-2">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button type="reset" class="btn btn-outline-danger px-4">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    

    <?php require_once 'views/layouts/footer.php' ?>
</main>
