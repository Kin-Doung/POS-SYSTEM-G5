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

    #inputMode {
        border-radius: 5px;
        padding: 5px;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875em;
    }

    .is-invalid~.invalid-feedback {
        display: block;
    }

    .manual-only,
    .barcode-only {
        display: none;
    }

    .product-name-input,
    .category-name-input {
        border-radius: 5px;
        padding: 5px;
    }

    body {
        background-color: #f8f9fa;
    }

    .modal-header {
        background-color: #007bff;
        color: white;
    }

    .custom-width {
        width: 200px;
    }

    .barcode-input {
        transition: background-color 0.3s;
    }

    .barcode-input.scanned {
        background-color: #e6ffe6;
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

        <div class="container mt-5">
            <div class="d-flex justify-content-end flex-column align-items-end gap-2 p-3 bg-light rounded shadow-sm">
                <!-- Preview Invoice Button -->
                <button type="button" id="previewInvoice" class="btn btn-primary custom-width" data-bs-toggle="modal" data-bs-target="#invoiceModal">
                    <i class="bi bi-eye-fill me-1"></i> Preview Invoice
                </button>

                <!-- Input Mode Selection -->
                <select id="inputMode" class="form-select custom-width">
                    <option value="manual">Manual Input</option>
                    <option value="barcode">Barcode Scan</option>
                </select>
            </div>
        </div>

        <div class="col-md-12 mt-5 mx-auto">
            <div class="card p-3" style="box-shadow: none; border: none">
                <form id="productForm" action="/inventory/store" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="input_mode" id="inputModeHidden" value="manual">
                    <div id="productFields" class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="image-field">Product Image</th>
                                    <th class="product-name-field">Product Name</th>
                                    <th class="category-name-field">Category Name</th>
                                    <th class="barcode-field">Barcode</th>
                                    <th>Quantity</th>
                                    <th>Price ($)</th>
                                    <th>Selling Price ($)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                                <tr class="product-row">
                                    <td class="image-field">
                                        <input type="file" name="image[]" class="form-control image-input" accept="image/*">
                                        <img src="" alt="Product Image" class="img-preview" style="display: none; width: 50px; height: 50px; margin: 0 auto;">
                                    </td>
                                    <td class="product-name-field">
                                        <input type="hidden" name="product_id[]" class="product-id-input">
                                        <select name="product_name[]" class="form-control product-select manual-only" required>
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
                                        <input type="text" name="product_name_text[]" class="form-control product-name-input barcode-only" placeholder="Product Name">
                                    </td>
                                    <td class="category-name-field">
                                        <select name="category_id[]" class="form-control category-select manual-only" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= htmlspecialchars($category['id']) ?>">
                                                    <?= htmlspecialchars($category['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="text" name="category_name[]" class="form-control category-name-input barcode-only" placeholder="Category Name">
                                    </td>
                                    <td class="barcode-field">
                                        <input type="text" name="barcode[]" class="form-control barcode-input" placeholder="Scan or enter barcode">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity-input" name="quantity[]" min="1" value="1" required>
                                        <div class="invalid-feedback">Please enter a valid quantity (1 or more).</div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control amount-input" name="amount[]" min="0" step="0.01" required>
                                        <div class="invalid-feedback">Please enter a valid price.</div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control selling-price-input" name="selling_price[]" min="0" step="0.01" required>
                                        <div class="invalid-feedback">Please enter a valid selling price.</div>
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
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        const baseUrl = window.location.pathname.includes('myapp') ? '/myapp' : '';
        let barcodeBuffer = '';
        let lastInputTime = 0;
        const debounceDelay = 50; // Adjust based on scanner speed (ms)

        // Toggle input mode
        function toggleInputMode() {
            const mode = $('#inputMode').val();
            if (mode === 'manual') {
                $('.manual-only').show();
                $('.barcode-only').hide();
                $('.barcode-field').hide();
                $('.image-field, .product-name-field, .category-name-field').show();
                $('.product-select').prop('required', true);
                $('.category-select').prop('required', true);
                $('.barcode-input').prop('required', false);
                $('.product-name-input, .category-name-input').prop('required', false);
            } else {
                $('.manual-only').hide();
                $('.barcode-only').show();
                $('.barcode-field').show();
                $('.image-field, .product-name-field, .category-name-field').show();
                $('.product-select').prop('required', false);
                $('.category-select').prop('required', false);
                $('.barcode-input').prop('required', true);
                $('.product-name-input, .category-name-input').prop('required', true);
                $('.barcode-input:first').focus();
            }
        }

        $('#inputMode').on('change', function() {
            $('#inputModeHidden').val(this.value);
            toggleInputMode();
        });
        toggleInputMode();

        // Add more rows
        $("#addMore").click(function() {
            var newRow = $(".product-row:first").clone();
            newRow.find("select").val("");
            newRow.find("input").val("");
            newRow.find(".quantity-input").val("1");
            newRow.find("img.img-preview").attr("src", "").hide();
            newRow.find("input").removeClass("is-invalid");

            newRow.find('.product-select').off('change').on('change', function() {
                handleProductSelect(this);
            });

            newRow.find('.barcode-input').off('input keypress'); // Handled by document-level keypress

            newRow.find('.removeRow').off('click').on('click', function() {
                if ($(".product-row").length > 1) {
                    $(this).closest("tr").remove();
                } else {
                    alert("At least one product row is required!");
                }
            });

            $("#productTableBody").append(newRow);
            toggleInputMode();
            $('.barcode-input:last').focus(); // Focus new row's barcode input
        });

        // Remove row
        $(document).on("click", ".removeRow", function() {
            if ($(".product-row").length > 1) {
                $(this).closest("tr").remove();
            } else {
                alert("At least one product row is required!");
            }
        });

        // Handle scanner input
        $(document).on('keypress', '.barcode-input', function(e) {
            const currentTime = new Date().getTime();
            const input = $(this);
            const char = String.fromCharCode(e.which);

            if (currentTime - lastInputTime > debounceDelay) {
                barcodeBuffer = '';
            }

            if (e.which === 13) { // Enter key
                if (barcodeBuffer) {
                    input.val(barcodeBuffer); // Store barcode in input
                    input.addClass('scanned');
                    setTimeout(() => input.removeClass('scanned'), 500); // Visual feedback
                    handleBarcodeInput(input, barcodeBuffer);
                    barcodeBuffer = '';
                }
                e.preventDefault();
            } else if (e.which >= 32 && e.which <= 126) { // Printable ASCII characters
                barcodeBuffer += char;
            }
            lastInputTime = currentTime;
        });

        // Handle product selection (manual mode)
        function handleProductSelect(selectElement) {
            const productId = selectElement.value;
            const row = $(selectElement).closest('tr');

            if (productId) {
                $.get(baseUrl + '/inventory/getProductDetails?id=' + productId)
                    .done(function(data) {
                        if (!data.error && data) {
                            row.find('.product-id-input').val(data.id || '');
                            row.find('.category-select').val(data.category_id || '');
                            row.find('.product-name-input').val(data.product_name || '');
                            row.find('.category-name-input').val(data.category_name || '');
                            const imgPreview = row.find('.img-preview');
                            if (data.image) {
                                imgPreview.attr('src', '/' + data.image).show();
                            } else {
                                imgPreview.attr('src', '').hide();
                            }
                            row.find('.quantity-input').val(data.quantity || '1').removeClass('is-invalid');
                            row.find('.amount-input').val(data.amount || 0);
                            row.find('.selling-price-input').val(data.selling_price || 0);
                            row.find('.barcode-input').val(data.barcode || '');
                        } else {
                            clearRowFields(row);
                        }
                    })
                    .fail(function(error) {
                        console.error('Fetch error for product ID ' + productId + ':', error);
                        clearRowFields(row);
                        alert('Error fetching product details.');
                    });
            }
        }

        // Handle barcode input
        function handleBarcodeInput(inputElement, barcode) {
            const row = $(inputElement).closest('tr');
            barcode = barcode.trim().replace(/[^0-9]/g, ''); // Clean non-numeric characters
            console.log('Processing barcode:', barcode); // Debug

            if (barcode) {
                inputElement.val(barcode); // Ensure barcode stays in input
                inputElement.prop('disabled', true).after('<span class="spinner-border spinner-border-sm"></span>');
                $.get(baseUrl + '/inventory/getProductByBarcode?barcode=' + encodeURIComponent(barcode))
                    .done(function(data) {
                        row.find('.spinner-border').remove();
                        inputElement.prop('disabled', false);
                        console.log('AJAX response:', data); // Debug
                        if (!data.error && data) {
                            row.find('.product-id-input').val(data.id || '');
                            row.find('.product-select').val(data.id || '');
                            row.find('.product-name-input').val(data.product_name || '');
                            row.find('.category-select').val(data.category_id || '');
                            row.find('.category-name-input').val(data.category_name || '');
                            const imgPreview = row.find('.img-preview');
                            if (data.image) {
                                imgPreview.attr('src', '/' + data.image).show();
                            } else {
                                imgPreview.attr('src', '').hide();
                            }
                            row.find('.quantity-input').val(data.quantity || '1').removeClass('is-invalid');
                            row.find('.amount-input').val(data.amount || 0);
                            row.find('.selling-price-input').val(data.selling_price || 0);
                            inputElement.val(barcode); // Keep barcode in input
                            if (row.is(':last-child')) {
                                $("#addMore").click();
                            } else {
                                // Focus next row's barcode input if available
                                const nextRow = row.next('.product-row');
                                if (nextRow.length) {
                                    nextRow.find('.barcode-input').focus();
                                }
                            }
                        } else {
                            clearRowFields(row);
                            inputElement.val(barcode); // Keep barcode on failure
                            alert('Product not found for barcode: ' + barcode);
                            inputElement.focus();
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        row.find('.spinner-border').remove();
                        inputElement.prop('disabled', false);
                        console.error('Barcode fetch error for ' + barcode + ':', {
                            status: jqXHR.status,
                            statusText: textStatus,
                            error: errorThrown,
                            response: jqXHR.responseText
                        });
                        clearRowFields(row);
                        inputElement.val(barcode); // Keep barcode on error
                        if (jqXHR.status === 404) {
                            alert('Barcode endpoint not found. Please check server routing.');
                        } else {
                            alert('Error fetching product for barcode ' + barcode + '. Status: ' + jqXHR.status);
                        }
                        inputElement.focus();
                    });
            }
        }

        // Clear row fields
        function clearRowFields(row) {
            row.find('.product-id-input').val('');
            row.find('.category-select').val('');
            row.find('.product-select').val('');
            row.find('.product-name-input').val('');
            row.find('.category-name-input').val('');
            row.find('.img-preview').attr('src', '').hide();
            row.find('.quantity-input').val('1').removeClass('is-invalid');
            row.find('.amount-input').val('');
            row.find('.selling-price-input').val('');
            // Note: barcode-input is not cleared to preserve scanned barcode
        }

        $(document).on("change", ".product-select", function() {
            if ($('#inputMode').val() === 'manual') {
                handleProductSelect(this);
            }
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
                const mode = $('#inputMode').val();
                let productName;

                if (mode === 'manual') {
                    productName = $(this).find('.product-select option:selected').text().trim();
                } else {
                    productName = $(this).find('.product-name-input').val() || 'Barcode: ' + ($(this).find('.barcode-input').val() || 'Unknown');
                }

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
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });

                doc.setFontSize(16);
                doc.setFont('helvetica', 'bold');
                doc.text('Engrty Alone', 105, 20, {
                    align: 'center'
                });

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
                    head: [
                        ['Image', 'Product Name', 'Quantity', 'Price ($)']
                    ],
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
                        0: {
                            cellWidth: 30
                        },
                        1: {
                            cellWidth: 70,
                            halign: 'left'
                        },
                        2: {
                            cellWidth: 40,
                            halign: 'left'
                        },
                        3: {
                            cellWidth: 40,
                            halign: 'left'
                        }
                    },
                    margin: {
                        left: 14,
                        right: 14
                    },
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
                doc.text(`Total Price: $${totalPrice.toFixed(2)}`, 196, finalY + 10, {
                    align: 'right'
                });
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

        // Form validation
        $('#productForm').on('submit', function(e) {
            const mode = $('#inputMode').val();
            const productInputs = mode === 'manual' ? $('.product-select') : $('.product-name-input');
            const barcodes = $('.barcode-input').map((_, input) => input.value).get();
            const quantities = $('.quantity-input');
            let hasErrors = false;

            quantities.each(function(index) {
                const q = parseInt(this.value);
                if (!q || q <= 0) {
                    $(this).addClass('is-invalid');
                    hasErrors = true;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (hasErrors) {
                alert('Please enter valid quantities (1 or more) for all products.');
                e.preventDefault();
                quantities.filter(':invalid').first().focus();
                return;
            }

            if (mode === 'manual') {
                const selectedProducts = productInputs.map((_, select) => select.value).get().filter(v => v);
                if (selectedProducts.length === 0) {
                    alert('Please select at least one product.');
                    e.preventDefault();
                    return;
                }
                const uniqueProducts = new Set(selectedProducts);
                if (uniqueProducts.size < selectedProducts.length) {
                    if (!confirm('You selected the same product multiple times. Quantities will be summed with existing stock. Continue?')) {
                        e.preventDefault();
                        return;
                    }
                }
            } else {
                const validBarcodes = barcodes.filter(b => b);
                if (validBarcodes.length === 0) {
                    alert('Please enter at least one valid barcode.');
                    e.preventDefault();
                    $('.barcode-input:first').focus();
                    return;
                }
            }
        });

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