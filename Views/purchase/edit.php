<div style="margin-left:250px">
    <?php require_once './views/layouts/nav.php' ?>
</div>
<?php require_once './views/layouts/side.php' ?>

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
                            <div class="mt-3 text-center">
                                <img id="preview"
                                     src="<?= $purchase['image'] ? htmlspecialchars($purchase['image']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==' ?>"
                                     alt="Image Preview"
                                     class="img-thumbnail"
                                     style="max-width: 120px; border-radius: 10px; <?= $purchase['image'] ? '' : 'display: none;' ?>">
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="submit" class="update">Update</button>
                            <a href="/purchase" class="cancel">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card {
        background-color: #ffffff;
        border-radius: 1.5rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    }

    .form-control, .form-select {
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 16px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #1a3c34;
        box-shadow: 0 0 0 0.2rem rgba(26, 60, 52, 0.25);
        outline: none;
    }

    .update, .cancel {
        padding: 12px 24px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-block;
        text-align: center;
    }

    .update {
        background-color: #1a3c34;
        color: #ffffff;
        border: none;
    }

    .update:hover {
        background-color: #163029;
    }

    .cancel {
        background-color: transparent;
        color: #1a3c34;
        border: 2px solid #1a3c34;
    }

    .cancel:hover {
        background-color: #eaf0ec;
        color: #1a3c34;
    }

    img.img-thumbnail {
        max-width: 120px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #ccc;
    }
</style>

<!-- JavaScript for Image Preview -->
<script>
    function updateImagePreview(input) {
        const preview = document.getElementById('preview');
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            // Fallback to existing image or hide preview if no image exists
            const existingImage = '<?= $purchase['image'] ? htmlspecialchars($purchase['image']) : '' ?>';
            if (existingImage) {
                preview.src = existingImage;
                preview.style.display = 'block';
            } else {
                preview.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
                preview.style.display = 'none';
            }
        }
    }

    // Attach event listener for file input changes
    document.getElementById('image').addEventListener('change', function (event) {
        updateImagePreview(this);
    });

    // Trigger preview update on page load to ensure existing image is shown
    document.addEventListener('DOMContentLoaded', function () {
        updateImagePreview(document.getElementById('image'));
    });
</script>