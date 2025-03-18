<?php require_once './views/layouts/side.php'; ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<?php require_once './views/layouts/header.php'; ?>

<div class="modal-content">
    <h2>Add New Product</h2>
    <form action="/purchase/store" method="POST" enctype="multipart/form-data" class="form-add-product">
        <!-- Product Name and Category in one line -->
        <div class="form-group">
            <div class="input-group">
                <label for="productName">Product Name:</label>
                <input type="text" id="productName"  class="input-create"  name="product_name" required />
            </div>
            <div class="input-group">
                <label for="productCategory">Category:</label>
                <select id="productCategory"   class="input-create"  name="category_id" required>
                    <option value="">Select Category</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No categories available</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- Image and Price under each other -->
        <div class="form-group">
            <div class="input-group">
                <label for="productImage">Upload Image:</label>
                <input type="file" id="productImage"   class="input-create"  name="image" accept="image/*" onchange="previewImage(event)" required />
                <img id="imagePreview" class="preview-image" src="" alt="Image Preview" style="display: none; max-width: 200px; height: auto;" /><br />
            </div>
            <div class="input-group">
                <label for="productPrice">Price:</label>
                <input type="number" id="productPrice"   class="input-create"   name="price" min="0" required />
            </div>
        </div>

        <!-- Submit Button centered below -->
        <button type="submit">Add product</button>
    </form>
</div>

</main>
<?php require_once './views/layouts/footer.php'; ?>
