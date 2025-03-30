<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php' ?>
<?php require_once './Databases/database.php' ?>


<main class="main-content position-relative max-height-vh-50 h-50 border-radius-lg ">
    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>

    <a href="/settings" class="btn-customm" style="width: 150px;">
        <i class="fas fa-arrow-left mt-4 "></i> Back
    </a>
    <div class="container" style="max-width: 1000px;">

        <div class="card shadow-sm p-5">
            <div class="card-header  text-dark text-center">
                <h4 class="mb-0">Edit Admin Settings</h4>
            </div>
            <div class="container mt-3">
                <form action="/settings/update?id=<?= $admin['id'] ?>" method="POST">
                    <div class="form-group">
                        <div class="group">
                            <label for="" class="form-label">Username:</label>
                            <input type="text" value=" <?= $admin['username'] ?>" name="username" class="form-control">
                        </div>
                        <div class="group">
                            <label for="" class="form-label">Password:</label>
                            <input type="text" value=" <?= $admin['password'] ?>" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="group">
                        <label for="" class="form-label">Email:</label>
                        <input type="text" value=" <?= $admin['email'] ?>" name="email" class="form-controls">
                    </div>
                    <div class="form-group">
                        <div class="group">
                            <label for="" class="form-label">Store_Name:</label>
                            <input type="text" value=" <?= $admin['store_name'] ?>" name="store_name" class="form-control">
                        </div>
                        <div class="group">
                            <label for="" class="form-label">Language:</label>
                            <select name="language" class="form-select" required>
                                <option value="en" <?= ($admin['language'] == 'en') ? 'selected' : '' ?>>English</option>
                                <option value="km" <?= ($admin['language'] == 'km') ? 'selected' : '' ?>>Khmer</option>
                            </select>
                        </div>

                    </div>
                    <div class="form-groupp mt-3">
                        <form action="upload.php" method="POST" enctype="multipart/form-data">
                            <label for="profile">Profile:
                                <?php if (!empty($admin['store_logo'])) : ?>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($admin['store_logo']) ?>"
                                        alt="Profile Image"
                                        class="rounded-circle shadow-sm"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    No Logo
                                <?php endif; ?>
                            </label>

                            <input type="file" name="profile" id="profile" accept="image/*" required>
                        </form>
                    </div>








                    <button type="submit" class="btn btn-success mt-3">Update</button>
                </form>
            </div>
        </div>
    </div>





    <link rel="stylesheet" href="../../views/assets/css/settings/edit.css">

    <?php require_once 'views/layouts/footer.php' ?>
</main>