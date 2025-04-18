<?php require_once './views/layouts/side.php'; ?>
<div style="margin-left: 250px;">
    <?php require_once './views/layouts/nav.php' ?>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    .head-add {
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
        color: #000;
    }

    .btn-preview {
        background: none;
        color: #000;
        z-index: 1000;
        text-decoration: underline;
        transition: all 0.3s ease-in-out;
    }

    .btn-preview:hover {
        border-radius: 5px;
        background: orange;
    }

    #addMore,
    #submitted {
        border-radius: 50px;
        padding: 8px 20px;
        background: darkblue;
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
        transition: all 0.2s ease-in-out;
        color: white;
    }

    #addMore:hover {
        background: rgb(10, 0, 95);
    }

    #submitted {
        background: green;
    }

    #submitted:hover {
        background: darkgreen;
    }

    .removeRow {
        background: none;
        color: red;
        text-decoration: underline;
        border: none;
        cursor: pointer;
    }

    .removeRow:hover {
        background: none;
    }

    .main-content {
        margin-top: -10px;
        width: 80%;
        border-radius: 10px;
        margin-left: auto;
        margin-right: auto;
    }

    .table {
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e6ed;
    }

    .table thead th {
        background-color: #f8fafc;
        color: #333;
        padding: 12px 16px;
        font-weight: 600;
        border: none;
        text-align: left;
        font-size: 14px;
        text-transform: uppercase;
    }

    .table tbody tr {
        border-bottom: 1px solid #e0e6ed;
    }

    .table td {
        padding: 12px 16px;
        vertical-align: middle;
        font-size: 14px;
        color: #333;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Image Input Styling */
    .image-add {
        display: none;
        /* Hide the default file input */
    }

    .image-label {
        display: inline-block;
        padding: 8px 16px;
        background-color: #f0f0f0;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        color: #333;
        transition: background-color 0.3s ease;
    }

    .image-label:hover {
        background-color: #e0e0e0;
    }

    .img-preview {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border: none;
        /* Removed border */
        border-radius: 4px;
        display: none;
        /* Hidden by default */
        cursor: pointer;
        /* Make the image clickable */
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 8px;
        border: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background-color: #1a3c34;
        color: white;
        border-bottom: none;
    }

    .modal-title {
        font-weight: 500;
    }

    .modal-body {
        padding: 20px;
        color: #333;
    }

    .modal-footer {
        border-top: none;
        padding: 15px 20px;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-1px);
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
    }

    /* Barcode Column Styling */
    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 150px;
    }

    .table-responsive {
        overflow-x: auto;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<main class="main-content create-content position-relative max-height-vh-100">
    <h2 class="text-center head-add" style="padding-top: 20px;">Add Stock Products</h2>
    <div class="d-flex justify-content-end align-item-center me-3">
        <button type="button" id="previewInvoice" class="btn-preview" data-bs-toggle="modal" data-bs-target="#invoiceModal">Preview Invoice</button>
    </div>
    <div class="col-md-12 mt-n3 mx-auto">
        <div class="card p-3" style="box-shadow: none; border: none">
            <form id="productForm" method="POST" action="/purchase/store" enctype="multipart/form-data">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Category</th>
                                <th>Product Name</th>
                                <th>Barcode</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            <!-- Rows will be dynamically populated -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" id="addMore">Add More</button>
                    <button type="submit" id="submitted">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoice Preview Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">Invoice Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Category</th>
                                <th>Product Name</th>
                                <th>Barcode</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceTableBody">
                            <!-- Rows will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" id="exportPDF" class="btn btn-primary">Export to PDF</button>
                    <button type="button" id="exportExcel" class="btn btn-primary">Export to Excel</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Function to load products from localStorage
    function loadProductsFromLocalStorage() {
        const products = JSON.parse(localStorage.getItem('products')) || [];
        const tableBody = $('#productTableBody');
        tableBody.empty();

        if (products.length === 0) {
            addNewRow();
        } else {
            products.forEach((product, index) => {
                const newRow = $(`
                <tr class="product-row">
                    <td>
                        <label for="image_${index}" class="image-label" style="${product.image ? 'display: none;' : 'display: inline-block;'}">Choose Image</label>
                        <input type="file" id="image_${index}" class="form-control image-add" name="image[]" accept="image/*">
                        <img src="${product.image || ''}" alt="Product Image" class="img-preview" style="${product.image ? 'display: block;' : 'display: none;'}">
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
                        <input type="text" class="form-control" name="product_name[]" value="${product.productName || ''}" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="barcode[]" value="${product.barcode || ''}">
                    </td>
                    <td>
                        <button type="button" class="removeRow" style="background: none; border: none; color: red; font-size: 15px; text-decoration: underline;">
                            <i class="fa-solid fa-trash"></i> Remove
                        </button>
                    </td>
                </tr>
            `);
                tableBody.append(newRow);
                newRow.find('select[name="category_id[]"]').val(product.categoryId || '');
                initImagePreview(newRow.find('.image-add'));
                initImageClick(newRow.find('.img-preview'));
            });
        }
    }

    // Function to save products to localStorage
    function saveProductsToLocalStorage() {
        const products = [];
        $('#productTableBody .product-row').each(function() {
            const imageSrc = $(this).find('.img-preview').attr('src') || '';
            const categoryId = $(this).find('select[name="category_id[]"]').val();
            const productName = $(this).find('input[name="product_name[]"]').val();
            const barcode = $(this).find('input[name="barcode[]"]').val();
            products.push({
                image: imageSrc,
                categoryId: categoryId,
                productName: productName,
                barcode: barcode
            });
        });
        localStorage.setItem('products', JSON.stringify(products));
    }

    // Function to add a new row
    function addNewRow() {
        const tableBody = $('#productTableBody');
        const rowCount = $('.product-row').length;
        const newRow = $(`
        <tr class="product-row">
            <td>
                <label for="image_${rowCount}" class="image-label">Choose Image</label>
                <input type="file" id="image_${rowCount}" class="form-control image-add" name="image[]" accept="image/*">
                <img src="" alt="Product Image" class="img-preview">
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
                <input type="text" class="form-control" name="barcode[]">
            </td>
            <td>
                <button type="button" class="removeRow" style="background: none; border: none; color: red; font-size: 15px; text-decoration: underline;">
                    <i class="fa-solid fa-trash"></i> Remove
                </button>
            </td>
        </tr>
    `);
        tableBody.append(newRow);
        initImagePreview(newRow.find('.image-add'));
        initImageClick(newRow.find('.img-preview'));
        saveProductsToLocalStorage();
    }

    // Add New Product Row
    $('#addMore').on('click', function() {
        addNewRow();
    });

    // Remove Product Row
    $('#productTableBody').on('click', '.removeRow', function() {
        const tbody = $(this).closest('tbody');
        if (tbody.find('tr').length > 1) {
            $(this).closest('tr').remove();
            saveProductsToLocalStorage();
        } else {
            alert('At least one product is required.');
        }
    });

    // Image Preview in Table
    function initImagePreview(fileInput) {
        fileInput.on('change', function(event) {
            const file = event.target.files[0];
            const preview = $(event.target).closest('td').find('.img-preview');
            const label = $(event.target).closest('td').find('.image-label');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).show();
                    label.hide();
                    saveProductsToLocalStorage();
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Make the image clickable to trigger file input
    function initImageClick(image) {
        image.on('click', function() {
            const fileInput = $(this).closest('td').find('.image-add');
            fileInput.click();
        });
    }

    // Update localStorage when inputs change
    $('#productTableBody').on('change', 'select[name="category_id[]"], input[name="product_name[]"], input[name="barcode[]"]', function() {
        saveProductsToLocalStorage();
    });

    // Load products from localStorage when the page loads
    $(document).ready(function() {
        loadProductsFromLocalStorage();
    });

    // Clear localStorage on form submission
    $('#productForm').on('submit', function() {
        localStorage.removeItem('products');
    });

    // Invoice Preview
    $('#previewInvoice').on('click', function() {
        const tableBody = $('#productTableBody');
        const invoiceTableBody = $('#invoiceTableBody');
        invoiceTableBody.empty();

        tableBody.find('.product-row').each(function() {
            const imageFile = $(this).find('input[type="file"]')[0].files[0];
            const imageSrc = $(this).find('.img-preview').attr('src');
            const categoryId = $(this).find('select[name="category_id[]"]').val();
            const categoryText = $(this).find('select[name="category_id[]"] option:selected').text();
            const productName = $(this).find('input[name="product_name[]"]').val();
            const barcode = $(this).find('input[name="barcode[]"]').val();

            if ((imageFile || imageSrc) && categoryId && productName) {
                const imageUrl = imageSrc || URL.createObjectURL(imageFile);
                const row = `
                <tr>
                    <td><img src="${imageUrl}" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover; border: none; border-radius: 4px;"></td>
                    <td>${categoryText}</td>
                    <td>${productName}</td>
                    <td>${barcode || 'N/A'}</td>
                </tr>
            `;
                invoiceTableBody.append(row);
            }
        });

        if (invoiceTableBody.children().length === 0) {
            invoiceTableBody.append('<tr><td colspan="4">No products to preview. Please fill in all fields (except barcode).</td></tr>');
        }
    });

    // Export to PDF
    $('#exportPDF').on('click', function() {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF();
        const table = document.querySelector('#invoiceTableBody');
        doc.autoTable({
            html: table,
            theme: 'striped',
            headStyles: {
                fillColor: [26, 60, 52]
            },
            columnStyles: {
                0: {
                    cellWidth: 20
                }
            },
            didDrawCell: function(data) {
                if (data.column.index === 0 && data.cell.raw) {
                    const img = data.cell.raw.querySelector('img');
                    if (img && img.src) {
                        doc.addImage(img.src, 'JPEG', data.cell.x + 2, data.cell.y + 2, 10, 10);
                    }
                }
            }
        });
        doc.save('invoice.pdf');
    });

    // Export to Excel
    $('#exportExcel').on('click', function() {
        const table = document.querySelector('#invoiceTableBody');
        const wb = XLSX.utils.table_to_book(table);
        XLSX.writeFile(wb, 'invoice.xlsx');
    });
</script>