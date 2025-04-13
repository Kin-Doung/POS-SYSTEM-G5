<?php
require_once './views/layouts/side.php';
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    .add-moree,
    .btn-submit {
        border-radius: 50px;
        background: blue;
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
        color: #fff;
    }

    .add-moree:hover {
        background: darkblue;
    }

    .btn-submit {
        background: green;
    }

    .btn-submit:hover {
        background: darkgreen;
        color: #fff;
        box-shadow: none;
    }

    .btn-preview {
        background: orange;
        color: #fff;
        box-shadow: none;
    }

    .btn-preview:hover {
        background: darkorange;
        color: #fff;
        box-shadow: none;
    }

    h2 {
        font-weight: bold;
        color: #000;
        font-family: "Poppins", sans-serif;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<main class="main-content create-content position-relative max-height-vh-100 h-100">
    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>

    <!-- Body -->
    <div class="add-stock mr-2">
        <h2 class="text-center head-add" style="padding-top: 10px;">Add Stock Products</h2>
        <div class="d-flex justify-content-end align-items-center me-3">
            <button type="button" id="previewInvoice" class="btn btn-preview" data-bs-toggle="modal" data-bs-target="#invoiceModal">Preview Invoice</button>
        </div>
        <div class="col-md-12 mt-5 mx-auto">
            <div class="card p-3" style="box-shadow: none; border: none">
                <form id="productForm" action="/inventory/store" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div id="productFields" class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Image</th>
                                    <th style="display: none;">Category</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price ($)</th>
                                    <th>Selling Price ($)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                                <tr class="product-row">
                                    <td>
                                        <input type="file" name="image[]" class="form-control image-input" accept="image/*" style="display: none;">
                                        <img src="" alt="Product Image" class="img-preview" style="display: none; width: 50px; height: 50px; margin: 0 auto;">
                                    </td>
                                    <td style="display: none;">
                                        <select name="category_id[]" class="form-control category-select" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= htmlspecialchars($category['id']) ?>">
                                                    <?= htmlspecialchars($category['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="product_name[]" class="form-control product-select" required>
                                            <option value="">Select Product</option>
                                            <?php if (!empty($inventory)): ?>
                                                <?php foreach ($inventory as $product): ?>
                                                    <option value="<?= htmlspecialchars($product['id']) ?>">
                                                        <?= htmlspecialchars($product['product_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option disabled>No Products Found</option>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity-input" name="quantity[]" min="0" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control amount-input" name="amount[]" min="0" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control selling-price-input" name="selling_price[]" min="0" step="0.01" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn removeRow" style="background: none; border: none; color: red; box-shadow: none; text-decoration: underline; font-size: 15px;">
                                            <i class="fa-solid fa-trash"></i> remove
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
    </div>

    <!-- Invoice Preview Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: #0096FF;">
                    <h5 class="modal-title" id="invoiceModalLabel">Invoice Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price ($)</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceTableBody"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right; font-weight: bold;">Total Price:</td>
                                <td id="totalPrice" style="font-weight: bold;">$0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" id="exportPDF" class="btn btn-primary" style="background: #F88379;">Export to PDF</button>
                    <button type="button" id="exportExcel" class="btn btn-primary" style="background: #4CBB17;">Export to Excel</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Add more rows
        $("#addMore").click(function() {
            var newRow = $(".product-row:first").clone();
            newRow.find("select").val("");
            newRow.find("input").val("");
            newRow.find("img.img-preview").attr("src", "").hide();

            newRow.find('.product-select').off('change').on('change', function() {
                handleProductSelect(this);
            });

            newRow.find('.removeRow').off('click').on('click', function() {
                if ($(".product-row").length > 1) {
                    $(this).closest("tr").remove();
                } else {
                    alert("At least one product row is required!");
                }
            });

            $("#productTableBody").append(newRow);
        });

        // Remove row
        $(document).on("click", ".removeRow", function() {
            if ($(".product-row").length > 1) {
                $(this).closest("tr").remove();
            } else {
                alert("At least one product row is required!");
            }
        });

        // Handle product selection
        function handleProductSelect(selectElement) {
            const productId = selectElement.value;
            const row = selectElement.closest('tr');

            if (productId) {
                fetch('/inventory/getProductDetails?id=' + productId)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.error && data) {
                            row.querySelector('.category-select').value = data.category_id || '';
                            const imgPreview = row.querySelector('.img-preview');
                            if (data.image) {
                                imgPreview.src = '/' + data.image;
                                imgPreview.style.display = 'block';
                            } else {
                                imgPreview.src = '';
                                imgPreview.style.display = 'none';
                            }
                            row.querySelector('.quantity-input').value = '';
                            row.querySelector('.amount-input').value = data.amount || 0;
                            row.querySelector('.selling-price-input').value = data.selling_price || 0;
                        } else {
                            row.querySelector('.category-select').value = '';
                            row.querySelector('.img-preview').src = '';
                            row.querySelector('.img-preview').style.display = 'none';
                            row.querySelector('.quantity-input').value = '';
                            row.querySelector('.amount-input').value = '';
                            row.querySelector('.selling-price-input').value = '';
                        }
                    })
                    .catch(error => console.error('Fetch error:', error));
            }
        }

        $(document).on("change", ".product-select", function() {
            handleProductSelect(this);
        });

        // Image preview
        $(document).on('change', '.image-input', function() {
            const file = this.files[0];
            const imgPreview = $(this).siblings('.img-preview')[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imgPreview.src = '';
                imgPreview.style.display = 'none';
            }
        });

        // Invoice Preview
        $('#previewInvoice').on('click', function() {
            const tableBody = $('#productTableBody');
            const invoiceTableBody = $('#invoiceTableBody');
            invoiceTableBody.empty();
            let totalPrice = 0;

            tableBody.find('.product-row').each(function() {
                const imageSrc = $(this).find('.img-preview').attr('src');
                const productName = $(this).find('.product-select option:selected').text().trim();
                const quantity = $(this).find('.quantity-input').val();
                const price = $(this).find('.amount-input').val();

                if (productName && quantity && price) {
                    const row = `
                        <tr>
                            <td><img src="${imageSrc || ''}" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover;"></td>
                            <td>${productName}</td>
                            <td>${quantity}</td>
                            <td>${price}</td>
                        </tr>
                    `;
                    invoiceTableBody.append(row);
                    totalPrice += parseFloat(price) * parseFloat(quantity);
                }
            });

            $('#totalPrice').text(`$${totalPrice.toFixed(2)}`);
            if (invoiceTableBody.children().length === 0) {
                invoiceTableBody.append('<tr><td colspan="4">No products to preview. Please fill in all fields.</td></tr>');
                $('#totalPrice').text('$0.00');
            }
        });

        // Export to PDF
        $('#exportPDF').on('click', function() {
            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });

                doc.setFontSize(16);
                doc.setFont('helvetica', 'bold');
                doc.text('Engrty Alone', 105, 20, { align: 'center' });

                doc.setFontSize(10);
                doc.setFont('helvetica', 'normal');
                const now = new Date();
                const dateTime = now.toLocaleString();
                doc.text(`Date: ${dateTime}`, 14, 30);
                doc.text('Address: 36 Terrick Rd, Ellington PE18 2NT, United Kingdom', 14, 35);
                doc.text('Phone: 067 67 67 67', 14, 40);

                const table = document.querySelector('#invoiceTableBody');
                if (!table || table.children.length === 0) {
                    alert('No data to export. Please add products to the preview.');
                    return;
                }

                const tableData = [];
                const imageData = [];
                let totalPrice = 0;

                $(table).find('tr').each(function() {
                    const imageSrc = $(this).find('td:eq(0) img').attr('src');
                    const productName = $(this).find('td:eq(1)').text();
                    const quantity = parseFloat($(this).find('td:eq(2)').text());
                    const price = parseFloat($(this).find('td:eq(3)').text());
                    const amount = quantity * price;
                    totalPrice += amount;

                    imageData.push(imageSrc);
                    tableData.push(['', productName, quantity.toString(), price.toFixed(2)]);
                });

                doc.autoTable({
                    head: [['Image', 'Product Name', 'Quantity', 'Price ($)']],
                    body: tableData,
                    startY: 50,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [200, 200, 200],
                        textColor: [0, 0, 0],
                        fontSize: 10,
                        fontStyle: 'bold'
                    },
                    bodyStyles: {
                        fontSize: 10,
                        textColor: [0, 0, 0],
                        fillColor: [255, 255, 255]
                    },
                    columnStyles: {
                        0: { cellWidth: 30 },
                        1: { cellWidth: 70, halign: 'left' },
                        2: { cellWidth: 40, halign: 'left' },
                        3: { cellWidth: 40, halign: 'left' }
                    },
                    margin: { left: 14, right: 14 },
                    didDrawCell: function(data) {
                        if (data.column.index === 0 && data.row.index >= 0) {
                            const imageSrc = imageData[data.row.index];
                            if (imageSrc) {
                                try {
                                    doc.addImage(imageSrc, 'JPEG', data.cell.x + 2, data.cell.y + 2, 10, 10);
                                } catch (imgError) {
                                    console.error('Error adding image to PDF:', imgError);
                                }
                            }
                        }
                    }
                });

                const finalY = doc.lastAutoTable.finalY || 50;
                doc.setFontSize(10);
                doc.setFont('helvetica', 'bold');
                doc.text(`Total Price: $${totalPrice.toFixed(2)}`, 196, finalY + 10, { align: 'right' });
                doc.setFont('helvetica', 'normal');
                doc.text('Thank you', 14, finalY + 20);

                doc.save('invoice.pdf');
            } catch (error) {
                console.error('Error generating PDF:', error);
                alert('Failed to generate PDF. Please check the console for errors.');
            }
        });

        // Export to Excel
        $('#exportExcel').on('click', function() {
            const table = document.querySelector('#invoiceTableBody');
            const wb = XLSX.utils.table_to_book(table);
            XLSX.writeFile(wb, 'invoice.xlsx');
        });

        // Warn about quantity summing
        $('#productForm').on('submit', function(e) {
            const productSelects = $('.product-select');
            const quantities = $('.quantity-input').map((_, input) => input.value).get();
            if (quantities.some(q => !q || q <= 0)) {
                alert('Please enter valid quantities for all products.');
                e.preventDefault();
                return;
            }
            const selectedProducts = productSelects.map((_, select) => select.value).get().filter(v => v);
            const uniqueProducts = new Set(selectedProducts);
            if (uniqueProducts.size < selectedProducts.length) {
                if (!confirm('You selected the same product multiple times. Quantities will be summed with existing stock. Continue?')) {
                    e.preventDefault();
                }
            }
        });

        // Bootstrap form validation
        const form = document.getElementById('productForm');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
</script>