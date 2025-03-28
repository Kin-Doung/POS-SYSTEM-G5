<?php
require_once './views/layouts/side.php';
?>

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
                    <!-- Initially, table header and one row -->
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
                                    <input type="text" class="form-control w-100" name="typeOfproducts[]" required>
                                </td>
                                <td>
                                    <button type="button" class="btn removeRow" style="background: none; border: none; color: red; box-shadow:none;text-decoration:underline;font-size:15px;">
                                        <i class="fa-solid fa-trash"></i>remove
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Submit and Add More Buttons -->
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
                    <!-- Total Price Display -->
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
            <input type="text" class="form-control w-100" name="typeOfproducts[]" required>
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
        const expiration = cells[5].querySelector('input').value;

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><img src="${URL.createObjectURL(productImage)}" alt="Product Image" style="width: 50px; height: 50px;"></td>
            <td>${category}</td>
            <td>${name}</td>
            <td>${quantity}</td>
            <td>$${price}</td>
            <td>${expiration}</td>
        `;
        invoiceTableBody.appendChild(newRow);

        totalPrice += price * quantity;
    });

    totalPriceElement.textContent = totalPrice.toFixed(2);
});

// Export to PDF
document.getElementById('exportPDF').addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const invoiceTable = document.getElementById('invoiceTableBody');
    const rows = invoiceTable.querySelectorAll('tr');

    let pdfRows = [];
    rows.forEach(row => {
        const columns = row.querySelectorAll('td');
        let pdfRow = [];
        columns.forEach(col => {
            pdfRow.push(col.textContent);
        });
        pdfRows.push(pdfRow);
    });

    doc.autoTable({
        head: [['Product Image', 'Category', 'Product Name', 'Quantity', 'Price ($)', 'Type of Products']],
        body: pdfRows
    });

    doc.save('invoice.pdf');
});

// Export to Excel
document.getElementById('exportExcel').addEventListener('click', function() {
    const invoiceTable = document.getElementById('invoiceTableBody');
    const rows = invoiceTable.querySelectorAll('tr');

    let excelData = [];
    rows.forEach(row => {
        const columns = row.querySelectorAll('td');
        let excelRow = [];
        columns.forEach(col => {
            excelRow.push(col.textContent);
        });
        excelData.push(excelRow);
    });

    const ws = XLSX.utils.aoa_to_sheet(excelData);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Invoice');
    XLSX.writeFile(wb, 'invoice.xlsx');
});
</script>
