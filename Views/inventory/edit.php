<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container mt-5">
        <h3 class="mb-4 text-center">Edit Product</h3>
        <form action="/inventory/update?id=<?= $inventory['id'] ?>" method="POST" enctype="multipart/form-data">

            <div class="row">
                <!-- Product Image -->
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Profile Image</label>
                    <input type="file" class="form-control" name="image" id="image" accept="image/*">
                    <!-- Display current image if available -->
                    <?php if (!empty($inventory['image'])) : ?>
                        <img src="<?= $inventory['image'] ?>" alt="Current Image" style="width: 80px; height: 80px; border-radius: 50%; margin-top: 10px;">
                    <?php endif; ?>
                </div>

                <!-- Category Selection -->
                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" name="category_id" id="category_id" required>
                        <option value="">Select Category</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['id']); ?>"
                                    <?= ($inventory['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No categories available</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Product Name -->
            <div class="mb-3">
                <label for="product_name" class="form-label">Name</label>
                <input type="text" class="form-control" name="product_name" id="product_name" value="<?= $inventory['product_name'] ?>" required>
            </div>

            <!-- Quantity -->
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" name="quantity" id="quantity" value="<?= $inventory['quantity'] ?>" required min="1">
            </div>

            <!-- Amount -->
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" class="form-control" name="amount" id="amount" value="<?= $inventory['amount'] ?>" required step="0.01" min="0">
            </div>

            <!-- Expiration Date -->
            <div class="mb-3">
                <label for="expiration_date" class="form-label">Expiration Date</label>
                <input type="date" class="form-control" name="expiration_date" id="expiration_date" value="<?= $inventory['expiration_date'] ?>" required>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">Update</button>
            </div>
        </form> 
    </div>
</main>
