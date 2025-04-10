<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-5">
                    <h2 class="mb-4 text-center" style="font-weight: bold; color: #1a3c34;">Edit Purchase</h2>

                    <form method="POST" action="/purchase/update/<?= htmlspecialchars($purchase['id']) ?>" enctype="multipart/form-data">
                        
                        <!-- Product Name -->
                        <div class="mb-4">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                   value="<?= htmlspecialchars($purchase['product_name']) ?>" required>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['id']) ?>"
                                        <?= $category['id'] == $purchase['category_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-4">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <?php if ($purchase['image']): ?>
                                <div class="mt-3 text-center">
                                    <img src="<?= htmlspecialchars($purchase['image']) ?>" alt="Current Image"
                                         class="img-thumbnail" style="max-width: 120px; border-radius: 10px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="submit" class="btn btn-success px-4">Update</button>
                            <a href="/purchase" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    .form-control, .form-select {
        border-radius: 12px;
        padding: 12px;
        font-size: 16px;
    }

    .btn-success {
        background-color: #1a3c34;
        border: none;
        border-radius: 10px;
        transition: 0.3s ease;
    }

    .btn-success:hover {
        background-color: #152e2a;
    }

    .btn-outline-secondary {
        border-radius: 10px;
        padding: 10px 20px;
        transition: 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #e2e6ea;
    }

    .card {
        background-color: #ffffff;
    }
</style>
