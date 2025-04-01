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
            <form id="productForm" method="POST" action="/purchase/store" enctype="multipart/form-data">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Expiration Date</th>
                            <th>Type</th>
                            <th>Action</th>
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
                        </tr>
                    </tbody>
                </table>
                <button type="button" id="addMore">Add More</button>
                <button type="submit">Submit</button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        if (e.target.classList.contains('removeRow')) {
            const tbody = e.target.closest('tbody');
            if (tbody.querySelectorAll('tr').length > 1) {
                e.target.closest('tr').remove();
            } else {
                alert('At least one product is required.');
            }
        }
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
                $.ajax({
                    url: '/purchase/get-existing-products',
                    type: 'GET',
                    data: {
                        category_id: categoryId || ''
                    },
                    dataType: 'json',
                    success: function(data) {
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
                                    ${product.product_name} (Stock: ${product.quantity})
                                </option>
                            `);
                            });
                        } else {
                            select.append('<option disabled>No products found</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        productNameCell.find('.loading-products').html('Failed to load products');
                    }
                });

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
                    <small>Current Stock: ${availableQty}</small>
                `); // No image preview for Old
                    priceInput.val(price);
                    quantityInput.val('');
                    if (categoryId) {
                        categorySelect.val(categoryId);
                    }
                });
            } else { // New or empty
                productNameCell.html(`<input type="text" class="form-control" name="product_name[]" required>`);
                imageCell.html(`
                <input type="file" class="form-control image-add" name="image[]" accept="image/*">
                <img src="" alt="Product Image" class="img-preview" style="display: none; width: 50px; height: 50px;">
            `);
                priceInput.val('');
                quantityInput.val('');
                categorySelect.val('');
                initImagePreview(imageCell.find('.image-add'));
            }
        });

        categorySelect.on('change', function() {
            if (typeSelect.val() === 'Old') {
                typeSelect.trigger('change');
            }
        });
    }

    $(document).ready(function() {
        $('.product-row').each(function() {
            handleProductTypeChange(this);
            initImagePreview($(this).find('.image-add'));
        });

        $('#productForm').on('submit', function(e) {
            // Uncomment to debug
            // e.preventDefault();
            // const formData = new FormData(this);
            // for (let [key, value] of formData.entries()) {
            //     console.log(`${key}: ${value}`);
            // }
        });
    });
</script>