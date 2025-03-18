<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <?php require_once './views/layouts/header.php'; ?>

    <div class="modal-content">
        <h2>Add New Product</h2>
        <form action="/purchase/store" method="POST" enctype="multipart/form-data">
            <label for="productName">Product Name:</label>
            <input type="text" id="productName" name="product_name" required /><br />

            <label for="productCategory">Category:</label>
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

            <label for="productImage">Upload Image:</label>
            <input type="file" id="productImage" name="image" accept="image/*" onchange="previewImage(event)" required /><br />
            <img id="imagePreview" class="preview-image" src="" alt="Image Preview" style="display: none; max-width: 200px; height: auto;" /><br />

            <label for="productPrice">Price:</label>
            <input type="number" id="productPrice" name="price" min="0" required /><br />

            <button type="submit">Submit</button>
        </form>

    </div>

</main>
<?php require_once './views/layouts/footer.php'; ?>
