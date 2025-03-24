<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="col-md-8 mt-5 mx-auto">
        <form action="/inventory/store" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <!-- Product Image -->
            <div class="form-group mb-3">
                <label for="image">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                <div class="invalid-feedback">Please upload a valid product image.</div>
            </div>

            <!-- Category Selection -->
            <div class="input-group mb-3">
                <select id="productCategory" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']); ?>">
                                <?= htmlspecialchars($category['name']); ?>
                            </option>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No categories available</option>
                    <?php endif; ?>
                </select><br />
            </div>

            <!-- Product Name -->
            <div class="form-group mb-3">
                <label for="product_name">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
                <div class="invalid-feedback">Product name is required.</div>
            </div>

            <!-- Quantity -->
            <div class="form-group mb-3">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                <div class="invalid-feedback">Quantity must be at least 1.</div>
            </div>

            <!-- Price -->
            <div class="form-group mb-3">
                <label for="amount">Price ($)</label>
                <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" required>
                <div class="invalid-feedback">Please enter a valid price.</div>
            </div>

            <!-- Expiration Date -->
            <div class="form-group mb-4">
                <label for="expiration_date">Expiration Date</label>
                <input type="date" class="form-control" id="expiration_date" name="expiration_date" required>
                <div class="invalid-feedback">Please select a valid expiration date.</div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100">Add Product</button>
        </form>

    </div>
</main>
