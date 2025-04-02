<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php'; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<main class="main-content create-content position-relative max-height-vh-100 h-100">
    <h2 class="text-center head-add" style="padding-top: 20px;">Add Stock Products</h2>
    <div class="d-flex justify-content-end align-item-center me-3">
        <button type="button" id="previewInvoice" class="btn btn-preview" data-bs-toggle="modal" data-bs-target="#invoiceModal">Preview Invoice</button>
    </div>
    <div class="col-md-12 mt-n3 mx-auto">
        <div class="card p-3" style="box-shadow: none;border:none">
            <form id="productForm" action="/purchase/store" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div id="productFields" class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Image</th>
                                <th>Category</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price ($)</th>
                                <th>Expiration date</th>
                                <th>Type of Products</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            <tr class="product-row">
                                <td>
                                    <input type="file" class="form-control image-add" name="image[]" accept="image/*">
                                    <img src="" alt="Product Image" class="img-preview" style="display: none; width: 50px; height: 50px;">
                                </td>
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
                                <td>
                                    <input type="text" class="form-control" name="product_name[]" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="quantity[]" min="1" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="amount[]" min="0" step="0.01" required>
                                </td>
                                <td>
                                    <input type="date" class="form-control w-100" name="expiration_date[]" value="${product.expiration}" required>
                                </td>
                                <td>
                                    <select name="typeOfproducts[]" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="New">New</option>
                                        <option value="Old">Old</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn removeRow" style="background: none; border: none; color: red; box-shadow:none;text-decoration:underline;font-size:15px;">
                                        <i class="fa-solid fa-trash"></i> Remove
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    <button type="button" id="addMore" class="add-moree">Add more</button>
                    <button type="submit" class="btn btn-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Invoice Preview -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">Invoice Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Image</th>
                                <th>Category</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price ($)</th>
                                <th>Type of Products</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceTableBody">
                            <!-- Dynamic Rows will be added here -->
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        <p>Total Price: $<span id="totalPrice">0</span></p>
                    </div>
                    <div class="button-container">
                        <button type="button" id="exportPDF" class="btn-system btn-export-pdf">Export to PDF</button>
                        <button type="button" id="exportExcel" class="btn-system btn-export-excel">Export to Excel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal-dialog {
            max-width: 80%;
            margin: 1.75rem auto;
        }

        .modal-content {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .modal-title {
            color: #333;
            font-weight: 600;
        }

        .table {
            margin-bottom: 20px;
        }

        .table th {
            background-color: #005670;
            color: white;
            font-weight: 500;
            padding: 12px;
        }

        .table td {
            padding: 10px;
            vertical-align: middle;
        }

        .d-flex.justify-content-end {
            padding: 10px 0;
            margin-top: 15px;
            border-top: 1px solid #eee;
        }

        .d-flex p {
            margin: 0;
            font-size: 1.1em;
            font-weight: 600;
            color: #333;
        }

        #totalPrice {
            color: #007a5e;
        }

        .button-container {
            margin-top: 20px;
            text-align: right;
        }

        .btn-system {
            padding: 10px 24px;
            margin-left: 10px;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-export-pdf {
            background-color: #d32f2f;
            color: white;
        }

        .btn-export-pdf:hover {
            background-color: #b71c1c;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-1px);
        }

        .btn-export-excel {
            background-color: #007a5e;
            color: white;
        }

        .btn-export-excel:hover {
            background-color: #005670;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-1px);
        }

        .btn-system:active {
            transform: translateY(1px);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
    </style>
</main>

<script>
    // Add New Product Row
    document.getElementById('addMore').addEventListener('click', function() {
        const tableBody = document.getElementById('productTableBody');
        const newRow = document.createElement('tr');
        newRow.classList.add('product-row');
        newRow.innerHTML = `
        <td>
            <input type="file" class="form-control image-add" name="image[]" accept="image/*">
            <img src="" alt="Product Image" class="img-preview" style="display: none; width: 50px; height: 50px;">
        </td>
        <td>
            <select name="category_id[]" class="form-control" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="text" class="form-control" name="product_name[]" required>
        </td>
        <td>
            <input type="number" class="form-control" name="quantity[]" min="1" required>
        </td>
        <td>
            <input type="number" class="form-control" name="amount[]" min="0" step="0.01" required>
        </td>
        <td>
            <input type="date" class="form-control" name="expiration_date[]" required>
        </td>

        <td>
            <select name="typeOfproducts[]" class="form-control" required>
                <option value="">Select Type</option>
                <option value="New">New</option>
                <option value="Old">Old</option>
            </select>
        </td>
        <td>
            <button type="button" class="removeRow" style="background: none; border: none; color: red; font-size: 15px; text-decoration: underline;">
                <i class="fa-solid fa-trash"></i> Remove
            </button>
        </td>
    `;
        tableBody.appendChild(newRow);
        handleProductTypeChange(newRow);
        initImagePreview(newRow.querySelector('.image-add'));
    });

    // Remove Product Row
    document.getElementById('productTableBody').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('removeRow')) {
            const tbody = e.target.closest('tbody');
            if (tbody.querySelectorAll('tr').length > 1) {
                e.target.closest('tr').remove();
            } else {
                alert('At least one product is required.');
            }
        }
    });

    // Preview Invoice
    document.getElementById('previewInvoice').addEventListener('click', function() {
        const productTableBody = document.getElementById('productTableBody');
        const invoiceTableBody = document.getElementById('invoiceTableBody');
        const totalPriceElement = document.getElementById('totalPrice');
        let totalPrice = 0;

        invoiceTableBody.innerHTML = '';
        const rows = productTableBody.querySelectorAll('tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const productImage = cells[0].querySelector('input[type="file"]')?.files[0] || null;
            const categorySelect = cells[1].querySelector('select');
            const category = categorySelect.options[categorySelect.selectedIndex].text;
            const productNameInput = cells[2].querySelector('input') || cells[2].querySelector('select');
            const name = productNameInput.value;
            const quantity = parseInt(cells[3].querySelector('input').value) || 0;
            const price = parseFloat(cells[4].querySelector('input').value) || 0;
            const typeSelect = cells[5].querySelector('select');
            const type = typeSelect.options[typeSelect.selectedIndex].text;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
            <td>${productImage ? `<img src="${URL.createObjectURL(productImage)}" alt="Product Image" style="width: 50px; height: 50px;">` : (cells[0].querySelector('.img-preview[src]') ? `<img src="${cells[0].querySelector('.img-preview').src}" style="width: 50px; height: 50px;">` : 'No Image')}</td>
            <td>${category}</td>
            <td>${name}</td>
            <td>${quantity}</td>
            <td>${price.toFixed(2)}</td>
            <td>${type}</td>
        `;
            invoiceTableBody.appendChild(newRow);
            totalPrice += price * quantity;
        });

        totalPriceElement.textContent = totalPrice.toFixed(2);
    });

    // Image Preview
    function initImagePreview(fileInput) {
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = event.target.closest('td').querySelector('.img-preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Handle Type of Product Change
    function handleProductTypeChange(row) {
        const typeSelect = $(row).find('select[name="typeOfproducts[]"]');
        const productNameCell = $(row).find('td:nth-child(3)');
        const imageCell = $(row).find('td:nth-child(1)');
        const priceInput = $(row).find('input[name="amount[]"]');
        const quantityInput = $(row).find('input[name="quantity[]"]');
        const categorySelect = $(row).find('select[name="category_id[]"]');

        typeSelect.on('change', function() {
            const selectedType = $(this).val();

            if (selectedType === 'Old') {
                productNameCell.html(`
                <select class="form-control existing-product-select" name="product_name[]" required>
                    <option value="">Select Existing Product</option>
                </select>
                <input type="hidden" name="inventory_id[]" value="">
            `);
                productNameCell.append('<div class="loading-products">Loading products...</div>');

                const categoryId = categorySelect.val();
                console.log('Category selected:', categoryId); // Debug: Check category ID

                $.ajax({
                    url: '/purchase/get-existing-products',
                    type: 'GET',
                    data: {
                        category_id: categoryId || ''
                    }, // Send empty if no category selected
                    dataType: 'json',
                    success: function(data) {
                        console.log('Retrieved products:', data); // Debug: Log the response
                        const select = productNameCell.find('.existing-product-select');
                        productNameCell.find('.loading-products').remove();

                        if (data && data.length > 0) {
                            $.each(data, function(index, product) {
                                select.append(`
                                <option value="${product.product_name}" 
                                        data-id="${product.id}"
                                        data-image="${product.image}"
                                        data-price="${product.amount}"
                                        data-quantity="${product.quantity}"
                                        data-category-id="${product.category_id}">
                                    ${product.product_name}
                                </option>
                            `);
                            });
                        } else {
                            select.append('<option disabled>No products found in inventory</option>');
                            console.log('No products available for category:', categoryId);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.status, xhr.responseText); // Debug: Log error details
                        productNameCell.find('.loading-products').html('Failed to load products: ' + error);
                    }
                });

                // In create.php, within handleProductTypeChange
                productNameCell.on('change', '.existing-product-select', function() {
                    const selectedOption = $(this).find('option:selected');
                    const inventoryId = selectedOption.data('id');
                    const imageUrl = selectedOption.data('image');
                    const price = selectedOption.data('price');
                    const availableQty = selectedOption.data('quantity');
                    const categoryId = selectedOption.data('category-id');

                    productNameCell.find('input[name="inventory_id[]"]').val(inventoryId);
                    imageCell.html(`
        <input type="hidden" name="image[]" value="${imageUrl}">
        <img src="${imageUrl}" alt="Product Image" class="img-preview" style="display: block; width: 50px; height: 50px;">
        <small>Current Stock: ${availableQty}</small>
    `);
                    categorySelect.val(categoryId);
                    priceInput.val(price);
                    quantityInput.val('').attr('max', null); // Clear quantity and remove max, user enters how much to add
                });
            } else if (selectedType === 'New' || selectedType === '') {
                productNameCell.html(`<input type="text" class="form-control" name="product_name[]" required>`);
                imageCell.html(`
                <input type="file" class="form-control image-add" name="image[]" accept="image/*">
                <img src="" alt="Product Image" class="img-preview" style="display: none; width: 50px; height: 50px;">
            `);
                categorySelect.val('');
                priceInput.val('');
                quantityInput.val('').removeAttr('max');
                productNameCell.find('input[name="inventory_id[]"]').remove();
                initImagePreview(imageCell.find('.image-add'));
            }
        });

        // Trigger change if category changes after "Old" is selected
        categorySelect.on('change', function() {
            if (typeSelect.val() === 'Old') {
                typeSelect.trigger('change'); // Re-fetch products when category changes
            }
        });
    }

    // Initialize existing rows
    $(document).ready(function() {
        $('.product-row').each(function() {
            handleProductTypeChange(this);
            initImagePreview($(this).find('.image-add'));
        });

        $('#productForm').on('submit', function(e) {
            console.log('Form submitted');
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
        });
    });
</script>