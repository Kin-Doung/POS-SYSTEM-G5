<?php
require_once './views/layouts/side.php';
?>
<main class="main-content create-content position-relative max-height-vh-100 h-100">
    <h2 class="text-center head-add" style="padding-top: 20px;">Add Stock Products</h2>
    <div class="col-md-12 mt-5 mx-auto">
        <div class="card p-3" style="box-shadow: none;">
            <form id="productForm" action="/inventory/store" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div id="productFields" class="table-responsive">
                    <!-- Initially, table header and one row -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Image</th>
                                <th>Category</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price ($)</th>
                                <th>Expiration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            <tr class="product-row">
                                <!-- Product Image -->
                                <td>
                                    <input type="file" class="form-control image-add" name="image[]" accept="image/*" required>
                                    <img src="" alt="Product Image" class="img-preview" style="display: none; width: 50px; height: 50px;">
                                </td>
                                <!-- Category Selection -->
                                <td>
                                    <select name="category_id[]" class="form-control" required>
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
                                </td>
                                <!-- Product Name -->
                                <td>
                                    <input type="text" class="form-control" name="product_name[]" required>
                                </td>
                                <!-- Quantity -->
                                <td>
                                    <input type="number" class="form-control" name="quantity[]" min="1" required>
                                </td>
                                <!-- Price -->
                                <td>
                                    <input type="number" class="form-control" name="amount[]" min="0" step="0.01" required>
                                </td>
                                <!-- Expiration Date -->
                                <td>
                                    <input type="date" class="form-control w-100" name="expiration_date[]" required>
                                </td>
                                <!-- Actions -->
                                <td>
                                <button type="button" class="btn removeRow" style="background: none; border: none; color: red; font-size: 20px;"><i class="fa-solid fa-trash"></i></button>

                                </td>

            
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Submit and Add More Buttons -->
                <div class="d-flex justify-content-end align-items-center">
                    <button type="button" id="addMore" class="btn btn-primary">Add more</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</main>

<style>
    /* Default style for inputs without data */
    .empty-input {
        background-color: #fff;
        border: 1px solid #ced4da;
    }

    /* Style for inputs with data */
    .filled-input {
        background-color: transparent;
        border: none;
    }

    /* Adjust the height and width of the table rows and cells */
    table.table-bordered {
        width: 100%;
        table-layout: fixed;
        /* Ensures the table columns don't expand too wide */
    }

    /* Style for the table headers */
    table.table-bordered th {
        padding: 5px 10px;
        font-size: 14px;
        height: 40px;
        text-align: center;
        /* Center-align the text in the headers */
    }

    /* Style for the table cells */
    table.table-bordered td {
        padding: 5px 10px;
        font-size: 14px;
        height: 40px;
        text-align: center;
        /* Center-align the text in the cells */
        vertical-align: middle;
        /* Vertically center-align the content */
    }

    /* Style for the input fields and selects */
    input.form-control,
    select.form-control {
        height: 30px;
        /* Set a smaller height for inputs */
        font-size: 14px;
        /* Smaller font size for inputs */
        text-align: center;
        /* Center-align the input text */
        padding: 0;
        /* Remove any extra padding */
        line-height: 30px;
        /* Vertically align text in the input */
        margin-top: 20px;
    }

    /* Style for the image preview */
    .img-preview {
        width: 30px;
        /* Reduce the width of the image preview */
        height: 30px;
        /* Reduce the height of the image preview */
        display: inline-block;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadSavedProducts(); // Load stored products when page loads
    });

    document.getElementById('addMore').addEventListener('click', function() {
        const productTableBody = document.getElementById('productTableBody');

        // Create a new row for the table
        const newRow = document.createElement('tr');
        newRow.classList.add('product-row');

        // Clone the first row
        const firstRow = document.querySelector('.product-row');
        newRow.innerHTML = firstRow.innerHTML;

        // Append new row
        productTableBody.appendChild(newRow);

        // Reset the image preview and input
        const newImageInput = newRow.querySelector('.image-add');
        const newImagePreview = newRow.querySelector('.img-preview');

        newImageInput.style.display = 'block';
        newImagePreview.style.display = 'none';
        newImageInput.value = '';

        // Apply empty input style to all inputs
        const newInputs = newRow.querySelectorAll('input, select');
        newInputs.forEach(input => {
            input.classList.add('empty-input');
            input.classList.remove('filled-input');
        });

        saveProductsToLocalStorage();
    });

    document.getElementById('productTableBody').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
            saveProductsToLocalStorage();
        }
    });

    document.getElementById('productTableBody').addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('image-add')) {
            const input = e.target;
            const row = input.closest('tr');
            const imagePreview = row.querySelector('.img-preview');

            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    imagePreview.style.display = 'inline';
                    input.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
            saveProductsToLocalStorage();
        }
    });

    document.getElementById('productTableBody').addEventListener('input', function(e) {
        const input = e.target;
        if (input.value.trim() !== '') {
            input.classList.add('filled-input');
            input.classList.remove('empty-input');
        } else {
            input.classList.add('empty-input');
            input.classList.remove('filled-input');
        }

        saveProductsToLocalStorage();
    });

    document.getElementById('productForm').addEventListener('submit', function() {
        localStorage.removeItem('savedProducts'); // Clear local storage on submit
    });

    function saveProductsToLocalStorage() {
        const rows = document.querySelectorAll('.product-row');
        const products = [];

        rows.forEach(row => {
            const product = {
                category: row.querySelector('[name="category_id[]"]').value,
                name: row.querySelector('[name="product_name[]"]').value,
                quantity: row.querySelector('[name="quantity[]"]').value,
                price: row.querySelector('[name="amount[]"]').value,
                expiration: row.querySelector('[name="expiration_date[]"]').value
            };
            products.push(product);
        });

        localStorage.setItem('savedProducts', JSON.stringify(products));
    }

    function loadSavedProducts() {
        const savedProducts = localStorage.getItem('savedProducts');
        if (!savedProducts) return;

        const products = JSON.parse(savedProducts);
        const productTableBody = document.getElementById('productTableBody');

        productTableBody.innerHTML = ''; // Clear table before adding saved products

        products.forEach(product => {
            const newRow = document.createElement('tr');
            newRow.classList.add('product-row');
            newRow.innerHTML = `
            <td>
                <input type="file" class="form-control image-add" name="image[]" accept="image/*" required>
                <img src="" alt="Product Image" class="img-preview" style="display: none; width: 50px; height: 50px;">
            </td>
            <td>
                <select name="category_id[]" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>" 
                            ${product.category == <?= $category['id'] ?> ? 'selected' : ''}>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="text" class="form-control" name="product_name[]" value="${product.name}" required>
            </td>
            <td>
                <input type="number" class="form-control" name="quantity[]" min="1" value="${product.quantity}" required>
            </td>
            <td>
                <input type="number" class="form-control" name="amount[]" min="0" step="0.01" value="${product.price}" required>
            </td>
            <td>
                <input type="date" class="form-control w-100" name="expiration_date[]" value="${product.expiration}" required>
            </td>
          <!-- Inside the 'product-row' template -->
            <td>
                 <button type="button" class=" removeRow" style="background: none; border: none; color: red; font-size: 20px;">
                 <i class="fa-solid fa-trash"></i>
                 </button>
            </td>

        `;

            productTableBody.appendChild(newRow);

            // Apply the appropriate styles for filled or empty inputs
            const newInputs = newRow.querySelectorAll('input, select');
            newInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    input.classList.add('filled-input');
                    input.classList.remove('empty-input');
                } else {
                    input.classList.add('empty-input');
                    input.classList.remove('filled-input');
                }
            });
        });
    }
</script>