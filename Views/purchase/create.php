<?php 
    require_once './views/layouts/side.php'; 
    require_once './views/layouts/header.php';
?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

    <div class="modal-content" style="max-width: 800px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; font-size: 20px; color: #333; margin-bottom: 20px;">Add New Product</h2>
        
        <form action="/purchase/store" method="POST" enctype="multipart/form-data">
            
            <!-- Product Name -->
            <div class="mb-3">
                <label for="productName" class="form-label" style="font-size: 14px; color: #555;">Product Name:</label>
                <input type="text" id="productName" name="product_name" class="form-control" style="font-size: 14px; padding: 8px; border-radius: 4px; border: 1px solid #ddd; width: 100%; margin-bottom: 10px;" required />
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label for="productCategory" class="form-label" style="font-size: 14px; color: #555;">Category:</label>
                <select id="productCategory" name="category_id" class="form-select" style="font-size: 14px; padding: 8px; border-radius: 4px; border: 1px solid #ddd; width: 100%; margin-bottom: 10px;" required>
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
                </select>
            </div>

            <!-- Product Image -->
            <div class="mb-3">
                <label for="productImage" class="form-label" style="font-size: 14px; color: #555;">Upload Image:</label>
                <input type="file" id="productImage" name="image" accept="image/*" class="form-control" style="font-size: 14px; padding: 8px; border-radius: 4px; border: 1px solid #ddd; width: 100%; margin-bottom: 10px;" onchange="previewImage(event)" required />
                <img id="imagePreview" class="preview-image" src="" alt="Image Preview" style="display: none; max-width: 180px; height: auto; margin-top: 10px;" />
            </div>

            <!-- Product Price -->
            <div class="mb-3">
                <label for="productPrice" class="form-label" style="font-size: 14px; color: #555;">Price:</label>
                <input type="number" id="productPrice" name="price" min="0" class="form-control" style="font-size: 14px; padding: 8px; border-radius: 4px; border: 1px solid #ddd; width: 100%; margin-bottom: 20px;" required />
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary" style="padding: 10px 18px; font-size: 14px; border-radius: 5px; background-color: #007bff; color: white; border: none; width: 60%; cursor: pointer;">
                    Submit
                </button>
            </div>
        </form>
    </div>

</main>
<?php require_once './views/layouts/footer.php'; ?>
