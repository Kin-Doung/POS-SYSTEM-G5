<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php'; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<<<<<<< HEAD
    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>
    <!-- End Navbar -->
    <!-- /// alert fuction// -->
    <!-- <div class="content mt-3 mb-3">
        <div class="row justify-content-center">
          
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg" style="height: 120px;">
                    <div class="card-body text-center mt-n4">
                        <div class="position-relative">
                            <i class="fas fa-warehouse text-success" style="font-size: 2.5rem;"></i>
                            <p class="card-text position-absolute top-0 end-0 m-2" style="font-size: 1.2rem;">20</p>
                        </div>
                        <h5 class="card-title mt-4">In Stock</h5>
                    </div>
                </div>
=======
    <div class="container">
        <h2>Edit Purchase</h2>
        <form method="POST" action="/purchase/update/<?= htmlspecialchars($purchase['id']); ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?= htmlspecialchars($purchase['product_name']); ?>" required>
>>>>>>> main
            </div>

            <!-- Profile Image Upload -->
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']); ?>" <?= $category['id'] == $purchase['category_id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($purchase['quantity']); ?>" min="1" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($purchase['price']); ?>" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="type_of_product" class="form-label">Type of Product</label>
                <input type="text" class="form-control" id="type_of_product" name="type_of_product" value="<?= htmlspecialchars($purchase['type_of_product']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <?php if (!empty($purchase['image'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($purchase['image']); ?>" alt="Current Image" style="width: 100px; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="/purchase" class="btn btn-secondary">Cancel</a>
        </form>

    </div>
</main>

<?php require_once './views/layouts/footer.php'; ?>
