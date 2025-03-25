<?php
require_once './views/layouts/side.php';

?>
<main class="main-content create-content position-relative max-height-vh-100 h-100">
    <h2 class="text-center head-add" style="padding-top: 20px;">Add stock product</h2>
    <div class="col-md-12 mt-5 mx-auto">
        <div class="card p-3" style="box-shadow: none;">
            <form id="productForm" action="/inventory/store" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="d-flex flex-row justify-content-start flex-wrap">
                    <!-- Product Image -->
                    <div class="form-group mb-3 col-12 col-md-2">
                        <label for="image">Product Image</label>
                        <input type="file" class="form-control image-add" id="image" name="image" accept="image/*" required>
                        <div class="invalid-feedback">Please upload a valid product image.</div>
                    </div>

                    <!-- Category Selection -->
                    <div class="form-group mb-3 col-12 col-md-2">
                        <label for="productCategory">Category</label>
                        <select id="productCategory" name="category_id" class="form-control" required>

                            <option value="">Select Category</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['id']) ?>">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option disabled>No Categories Found</option>
                            <?php endif; ?>

                        </select>
                    </div>

                    <!-- Product Name -->
                    <div class="form-group mb-3 col-12 col-md-2">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                        <div class="invalid-feedback">Product name is required.</div>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group mb-3 col-12 col-md-2">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                        <div class="invalid-feedback">Quantity must be at least 1.</div>
                    </div>

                    <!-- Price -->
                    <div class="form-group mb-3 col-12 col-md-2">
                        <label for="amount">Price ($)</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" required>
                        <div class="invalid-feedback">Please enter a valid price.</div>
                    </div>
                    <input type="hidden" id="total_price" name="total_price">
                    <!-- Expiration Date -->
                    <div class="form-group mb-4 col-12 col-md-2">
                        <label for="expiration_date">Expiration Date</label>
                        <input type="date" class="form-control w-100" id="expiration_date" name="expiration_date" required>
                        <div class="invalid-feedback">Please select a valid expiration date.</div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end align-items-center">
                    <button type="button" id="addMore" class="btn btn-primary">Add more</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>

            <!-- Display Saved Data (Dynamically Added) -->
            <div id="savedProducts" class="mt-5">
                <h4>Saved Products:</h4>
                <div id="productList" class="d-flex flex-column"></div>
            </div>
        </div>
    </div>
</main>