<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php'; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
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
                                <th>Type of Products</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            <tr class="product-row">
                                <td>
                                    <input type="file" class="form-control image-add" name="image[]" accept="image/*" required>
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
                    <div>
                        <button type="button" id="exportPDF" class="btn-export">Export to PDF</button>
                        <button type="button" id="exportExcel" class="btn-export-excel">Export to Excel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Add New Product Row Function
    document.getElementById('addMore').addEventListener('click', function() {
        const tableBody = document.getElementById('productTableBody');
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
            <select name="typeOfproducts[]" class="form-control" required>
                <option value="">Select Type</option>
                <option value="Electronic">New</option>
                <option value="Clothing">Old</option>

            </select>
        </td>
        <td>
            <button type="button" class="removeRow" style="background: none; border: none; color: red; font-size: 15px; text-decoration: underline;">
                <i class="fa-solid fa-trash"></i> Remove
            </button>
        </td>
    `;
        tableBody.appendChild(newRow);
    });

    // Remove Product Row Function
    document.getElementById('productTableBody').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
        }
    });

    // Preview Invoice Function
    document.getElementById('previewInvoice').addEventListener('click', function() {
        const productTableBody = document.getElementById('productTableBody');
        const invoiceTableBody = document.getElementById('invoiceTableBody');
        const totalPriceElement = document.getElementById('totalPrice');
        let totalPrice = 0;

        invoiceTableBody.innerHTML = ''; // Clear previous table rows

        const rows = productTableBody.querySelectorAll('tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const productImage = cells[0].querySelector('input[type="file"]').files[0];
            const category = cells[1].querySelector('select').value;
            const name = cells[2].querySelector('input').value;
            const quantity = parseInt(cells[3].querySelector('input').value);
            const price = parseFloat(cells[4].querySelector('input').value);
            const expiration = cells[5].querySelector('select').value;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
            <td><img src="${URL.createObjectURL(productImage)}" alt="Product Image" style="width: 50px; height: 50px;"></td>
            <td>${category}</td>
            <td>${name}</td>
            <td>${quantity}</td>
            <td>${price}</td>
            <td>${expiration}</td>
        `;
            invoiceTableBody.appendChild(newRow);
            totalPrice += price * quantity;
        });

        totalPriceElement.textContent = totalPrice.toFixed(2);
    });



    document.querySelectorAll('.image-add').forEach(function(input) {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = event.target.closest('td').querySelector('.img-preview');
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';  // Show image preview
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    });


    $(document).ready(function() {
    // Function to handle type of product selection
    function handleProductTypeChange(row) {
        const typeSelect = $(row).find('select[name="typeOfproducts[]"]');
        const productNameCell = $(row).find('td:nth-child(3)'); // Product Name cell
        const imageCell = $(row).find('td:nth-child(1)'); // Image cell
        const priceInput = $(row).find('input[name="amount[]"]');
        const quantityInput = $(row).find('input[name="quantity[]"]');
        
        typeSelect.on('change', function() {
            const selectedType = $(this).val();
            
            if (selectedType === 'Old') {
                // Replace text input with select dropdown for existing products
                const currentValue = productNameCell.find('input[type="text"]').val();
                productNameCell.html(`
                    <select class="form-control existing-product-select" name="product_name[]" required>
                        <option value="">Select Existing Product</option>
                        <!-- Options will be loaded via AJAX -->
                    </select>
                `);
                
                // Show loading indicator
                productNameCell.append('<div class="loading-products">Loading products...</div>');
                
                // Fetch existing products via AJAX
                $.ajax({
                    url: '/purchase/get-existing-products', // Create this endpoint in your backend
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        const select = productNameCell.find('.existing-product-select');
                        productNameCell.find('.loading-products').remove();
                        
                        if (data.length > 0) {
                            $.each(data, function(index, product) {
                                select.append(`
                                    <option value="${product.id}" 
                                            data-image="${product.image_url}"
                                            data-price="${product.price}"
                                            data-quantity="${product.quantity}">
                                        ${product.name}
                                    </option>
                                `);
                            });
                        } else {
                            select.append('<option disabled>No products found in inventory</option>');
                        }
                    },
                    error: function() {
                        productNameCell.find('.loading-products').html('Failed to load products');
                    }
                });
                
                // Handle selection of existing product
                productNameCell.on('change', '.existing-product-select', function() {
                    const selectedOption = $(this).find('option:selected');
                    const imageUrl = selectedOption.data('image');
                    const price = selectedOption.data('price');
                    const availableQty = selectedOption.data('quantity');
                    
                    // Set the price from inventory
                    priceInput.val(price);
                    
                    // Update the image preview
                    imageCell.html(`
                        <input type="hidden" name="image[]" value="${imageUrl}">
                        <input type="hidden" name="existing_product_id[]" value="${$(this).val()}">
                        <img src="${imageUrl}" alt="Product Image" class="img-preview" style="display: block; width: 50px; height: 50px;">
                        <small>Available: ${availableQty}</small>
                    `);
                    
                    // Set max quantity to available quantity
                    quantityInput.attr('max', availableQty);
                });
                
            } else if (selectedType === 'New' || selectedType === '') {
                // Restore original text input and file upload
                productNameCell.html(`<input type="text" class="form-control" name="product_name[]" required>`);
                
                // Restore original image upload
                imageCell.html(`
                    <input type="file" class="form-control image-add" name="image[]" accept="image/*" required>
                    <img src="" alt="Product Image" class="img-preview" style="display: none; width: 50px; height: 50px;">
                `);
                
                // Clear price and remove max quantity restriction
                priceInput.val('');
                quantityInput.removeAttr('max');
                
                // Reinitialize the image preview functionality
                initImagePreview(imageCell.find('.image-add'));
            }
        });
    }
    
    // Function to initialize image preview
    function initImagePreview(fileInput) {
        fileInput.on('change', function() {
            const file = this.files[0];
            const imgPreview = $(this).siblings('.img-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.attr('src', e.target.result);
                    imgPreview.show();
                };
                reader.readAsDataURL(file);
            } else {
                imgPreview.hide();
            }
        });
    }
    
    // Initialize for existing rows
    $('.product-row').each(function() {
        handleProductTypeChange(this);
        initImagePreview($(this).find('.image-add'));
    });
    
    // Handle adding new rows
    $('#addMore').on('click', function() {
        const newRow = $('#productTableBody tr:first').clone();
        
        // Reset input values
        newRow.find('input[type="text"], input[type="number"]').val('');
        newRow.find('select').val('');
        
        // Reset image preview
        newRow.find('.img-preview').hide().attr('src', '');
        newRow.find('.image-add').val('');
        
        // Add to table
        $('#productTableBody').append(newRow);
        
        // Initialize handlers for new row
        handleProductTypeChange(newRow);
        initImagePreview(newRow.find('.image-add'));
    });
    
    // Handle row removal (delegated event)
    $('#productTableBody').on('click', '.removeRow', function() {
        // Don't remove if it's the only row
        if ($('#productTableBody tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('At least one product is required.');
        }
    });
});


</script>