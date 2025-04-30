<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php' ?>
<?php require_once './Databases/database.php' ?>

<?php
// Debugging: Log to console or file instead of displaying
if (!empty($_POST) || !empty($_FILES)) {
    error_log("POST: " . print_r($_POST, true));
    error_log("FILES: " . print_r($_FILES, true));
}
?>

<main class="main-content position-relative max-height-vh-50 h-50 border-radius-lg">
    <div class="container mt-4">
        <a href="/settings" class="btn btn-back mb-4">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        <div class="account-card shadow-sm">
            <div class="account-card-header">
                <h4 class="mb-0">Edit Admin Settings</h4>
            </div>
            <div class="account-details p-4">
                <form action="/settings/update?id=<?= $admin['id'] ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    <!-- Profile Image Section at the Top -->
                    <div class="text-center mb-4">
                        <div class="account-field">
                            <label for="store_logo" class="form-label">Profile Logo</label>
                            <div class="d-flex justify-content-center mb-3">
                                <?php if (!empty($admin['store_logo'])) : ?>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($admin['store_logo']) ?>"
                                         alt="Profile Image" class="profile-img">
                                <?php else: ?>
                                    <span class="no-logo">No Logo</span>
                                <?php endif; ?>
                            </div>
                            <input type="file" id="store_logo" name="store_logo" accept="image/*" class="form-control w-100">
                        </div>
                    </div>
                    <!-- Other Fields -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="account-field">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username" class="form-control"
                                       value="<?= htmlspecialchars($admin['username']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="account-field">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control"
                                       placeholder="Leave blank to keep current password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="account-field">
                                <label for="store_name" class="form-label">Store Name</label>
                                <input type="text" id="store_name" name="store_name" class="form-control"
                                       value="<?= htmlspecialchars($admin['store_name']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="account-field">
                                <label for="language" class="form-label">Language</label>
                                <select id="language" name="language" class="form-select" required>
                                    <option value="en" <?= $admin['language'] == 'en' ? 'selected' : '' ?>>English</option>
                                    <option value="km" <?= $admin['language'] == 'km' ? 'selected' : '' ?>>Khmer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="account-field">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                       value="<?= htmlspecialchars($admin['email']) ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-update">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
    .account-card {
        max-width: 1000px;
        margin: 0 auto;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        background: #ffffff;
    }
    .account-card-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 20px;
        text-align: center;
        border-radius: 15px 15px 0 0;
    }
    .account-details {
        padding: 30px;
    }
    .account-field {
        margin-bottom: 20px;
    }
    .account-field label {
        color: #343a40;
        font-weight: 600;
    }
    .account-field .form-control,
    .account-field .form-select {
        height: 48px; /* Set a consistent height for all inputs */
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding: 10px;
        font-size: 16px;
        display: flex; /* Use flexbox to center text vertically */
        align-items: center; /* Center text vertically */
        line-height: 1.5; /* Ensure consistent line height */
    }
    .account-field .form-select {
        padding-right: 30px; /* Add padding to accommodate the dropdown arrow */
        background-position: right 10px center; /* Adjust dropdown arrow position */
    }
    .account-field .form-control:focus,
    .account-field .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }
    .profile-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #e9ecef;
        transition: transform 0.3s ease;
    }
    .profile-img:hover {
        transform: scale(1.05);
    }
    .no-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 120px;
        height: 120px;
        background: #e9ecef;
        border-radius: 50%;
        color: #6c757d;
        font-size: 16px;
        text-align: center;
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        border-radius: 25px;
        background: #f8f9fa;
        color: #007bff;
        border: 1px solid #007bff;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .btn-back:hover {
        background: #007bff;
        color: white;
        border-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }
    .btn-update {
        padding: 12px 30px;
        border-radius: 25px;
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .btn-update:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    @media (max-width: 576px) {
        .account-card {
            margin: 20px;
        }
    }
</style>