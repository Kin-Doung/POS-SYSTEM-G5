<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php'; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    .head-add{
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
        color: #000;
    }
    .btn-preview{
        background: none;
        color: #000;
        z-index: 1000;
        text-decoration: underline;
        transition: all 0.3s ease-in-out;
    }
    .btn-preview:hover{
        border-radius: 5px;
        background: orange;
    }
    #addMore , #submitted{
        border-radius: 50px;
        padding: 8px 20px;
        background: darkblue;
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
        transition: all 0.2s ease-in-out;
    }
    #addMore:hover{
        background: rgb(10, 0, 95);  
    }
    #submitted{
        background: green;
    }
    #submitted:hover{
        background: darkgreen;
    }
    .removeRow{
        background: none;
        color: red;
        text-decoration: underline;
    }
    .removeRow:hover{
        background: none;
    }
    
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<main class="main-content create-content position-relative max-height-vh-100 h-100">
    <h2 class="text-center head-add" style="padding-top: 20px;">Add Stock Products</h2>
    <div class="d-flex justify-content-end align-item-center me-3">
        <button type="button" id="previewInvoice" class=" btn-preview" data-bs-toggle="modal" data-bs-target="#invoiceModal">Preview Invoice</button>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        <tr class="product-row">
                            <td>
                                <input type="file" class="form-control image-add" name="image[]" accept="image/*">
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
                                <button type="button" class="removeRow">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between">

                <button type="button" id="addMore">Add More</button>
                <button type="submit" id="submitted" >Submit</button>
                </div>
                
            </form>
        </div>
    </div>

        </div>
    </div>
    <script src="../../views/assets/js/demo/chart-area-demo.js"></script>

    <script>
        // Add New Product Row
        $('#addMore').on('click', function() {
            const tableBody = $('#productTableBody');
            const newRow = $(`
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
                    <button type="button" class="removeRow" style="background: none; border: none; color: red; font-size: 15px; text-decoration: underline;">
                        <i class="fa-solid fa-trash"></i> Remove
                    </button>
                </td>
            </tr>
        `);
            tableBody.append(newRow);
            initImagePreview(newRow.find('.image-add'));
        });

        // Remove Product Row
        $('#productTableBody').on('click', '.removeRow', function() {
            const tbody = $(this).closest('tbody');
            if (tbody.find('tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                alert('At least one product is required.');
            }
        });

        // Image Preview
        function initImagePreview(fileInput) {
            fileInput.on('change', function(event) {
                const file = event.target.files[0];
                const preview = $(event.target).closest('td').find('.img-preview');
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Invoice Preview
        $('#previewInvoice').on('click', function() {
            const tableBody = $('#productTableBody');
            const invoiceTableBody = $('#invoiceTableBody');
            let totalPrice = 0;
            invoiceTableBody.empty(); // Clear previous rows

            tableBody.find('.product-row').each(function() {
                const image = $(this).find('input[type="file"]')[0].files[0];
                const category = $(this).find('select[name="category_id[]"]').val();
                const productName = $(this).find('input[name="product_name[]"]').val();

                // Create invoice table rows
                const row = `
                <tr>
                    <td><img src="${URL.createObjectURL(image)}" alt="Product Image" style="width: 50px; height: 50px;"></td>
                    <td>${category}</td>
                    <td>${productName}</td>
                </tr>
            `;
                invoiceTableBody.append(row);
            });

            // Update total price
            $('#totalPrice').text(totalPrice.toFixed(2));
        });

        // Export to PDF
        $('#exportPDF').on('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            const table = document.querySelector('#invoiceTableBody');
            doc.autoTable({
                html: table
            });
            doc.save('invoice.pdf');
        });

        // Export to Excel
        $('#exportExcel').on('click', function() {
            const table = document.querySelector('#invoiceTableBody');
            const wb = XLSX.utils.table_to_book(table);
            XLSX.write(wb, {
                bookType: 'xlsx',
                type: 'binary'
            });
            XLSX.writeFile(wb, 'invoice.xlsx');
        });
    </script>