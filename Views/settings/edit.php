<div class="container">
    <form action="/settings/update/<?= $admin['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label class="form-label">Username:</label>
            <input type="text" value="<?= htmlspecialchars($admin['username'] ?? '') ?>" name="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Email:</label>
            <input type="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Password (Leave blank to keep current password):</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group">
            <label class="form-label">Store Name:</label>
            <input type="text" value="<?= isset($admin['store_name']) ? htmlspecialchars($admin['store_name']) : '' ?>"
                name="store_name" class="form-control" required>
        </div>


        <div class="form-group">
            <label class="form-label">Store Logo:</label>
            <input type="file" name="store_logo" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label class="form-label">Language:</label>
            <select name="language" class="form-control" required>
                <option value="en" <?= ($admin['language'] ?? '') == 'en' ? 'selected' : '' ?>>English</option>
                <option value="km" <?= ($admin['language'] ?? '') == 'km' ? 'selected' : '' ?>>Khmer</option>
            </select>
        </div>


        <button type="submit" class="btn btn-success mt-3">Update</button>
    </form>
</div>