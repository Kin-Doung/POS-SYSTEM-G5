<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php' ?>
<?php require_once './Databases/database.php' ?>
<?php
var_dump($_POST); // Check if form data is coming through
var_dump($_FILES); // Check if the file is being uploaded
?>


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
                <form action="/settings/update?id=<?= $admin['id'] ?>" method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <div class="group">
                            <label for="" class="form-label">Username:</label>

                            <input type="text" value="<?= htmlspecialchars($admin['username']) ?>" name="username" class="form-control">

                        </div>
                        <div class="group">
                            <label for="" class="form-label">Password:</label>
                            <input type="password" value=" <?= $admin['password'] ?>" name="password" class="form-control" placeholder="Leave blank to keep current password">

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


                    <div class="form-group mt-3">
                        <label for="profile">Profile:</label>
                        <?php if (!empty($admin['store_logo'])) : ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($admin['store_logo']) ?>"
                                alt="Profile Image"
                                class="rounded-circle shadow-sm"
                                style="width: 50px; height: 50px; object-fit: cover;">
                        <?php else: ?>
                            No Logo
                        <?php endif; ?>
                        <input type="file" name="store_logo" id="profile" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-success mt-3">Update</button>
                </form>
            </div>
        </div>
    </div>





    <link rel="stylesheet" href="../../views/assets/css/settings/edit.css">

    <?php require_once 'views/layouts/footer.php' ?>
</main>