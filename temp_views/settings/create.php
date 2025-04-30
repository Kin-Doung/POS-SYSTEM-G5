<?php require_once './views/layouts/side.php'; ?>
<?php require_once './views/layouts/header.php'; ?>

<main class="container mt-5">
    <div class="card shadow p-4 mx-auto" style="max-width: 500px;">
        <h4 class="text-center mb-3">Admin Setting</h4>
        <form action="/settings/store" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Store Name</label>
                <input type="text" name="store_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Store Logo</label>
                <input type="file" name="store_logo" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Language</label>
                <select name="language" class="form-select">
                    <option value="en">English</option>
                    <option value="km">Khmer</option>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Create Admin</button>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
    </div>
</main>

<?php require_once 'views/layouts/footer.php'; ?>
