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

    <div class="container mt-4" style="max-width: 1000px;">
        <a href="/settings" class="btn-customm" style="width: 150px;">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <div class="card shadow-sm">
            <div class="card-header  text-white text-center">
                <h4 class="mb-0">Edit Admin Settings</h4>
            </div>
            <div class="container">
                <form action="/settings/update?id=<?= $user['id'] ?>" method="POST">
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

                    <div class="form-group">
                        <label for="" class="form-label">Email:</label>
                        <input type="text" value=" <?= $admin['email'] ?>" name="email" class="form-control">
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
                    <div class="form-group">
                        <form action="upload.php" method="POST" enctype="multipart/form-data">
                            <label for="profile">Store Logo:</label>
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