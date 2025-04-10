<?php
require_once './views/layouts/side.php';
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    .add-moree, .btn-submit {
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
        background: green;
        color: #fff;
        box-shadow: none;
    }
    .btn-preview {
        background: orange;
        color: #fff;
        box-shadow: none;
    }
    .btn-preview:hover {
        background: orange;
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
        <div class="d-flex justify-content-end align-item-center me-3">
            <button type="button" id="previewInvoice" class="btn btn-preview" data-bs-toggle="modal" data-bs-target="#invoiceModal">Preview Invoice</button>
        </div>
        <div class="col-md-12 mt-5mt-n3 mx-auto">
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                                <tr class="product-row">
                                    <td>
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
                                        <input type="number" class="form-control quantity-input" name="quantity[]" min="1" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control amount-input" name="amount[]" min="0" step="0.01" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn removeRow" style="background: none; border: none; color: red; box-shadow: none; text-decoration: underline; font-size: 15px;">
                                            <i class="fa-solid fa-trash"></i>remove
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
                        <tbody id="invoiceTableBody">
                            <!-- Rows will be dynamically added here -->
                        </tbody>
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
<script>
    $(document).ready(function() {
        $("#addMore").click(function() {
            // Clone the first row
            var newRow = $(".product-row:first").clone();

            // Clear input values
            newRow.find("select").val(""); // Clear dropdowns
            newRow.find("input").val(""); // Clear input fields
            newRow.find("img.img-preview").attr("src", "").hide(); // Hide image preview

            // Remove previously set event listeners and attach new ones
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

            // Append the new row
            $("#productTableBody").append(newRow);
        });

        // Remove row functionality
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

                            row.querySelector('.quantity-input').value = data.quantity || 0;
                            row.querySelector('.amount-input').value = data.amount || 0;
                        } else {
                            row.querySelector('.category-select').value = '';
                            row.querySelector('.img-preview').src = '';
                            row.querySelector('.img-preview').style.display = 'none';
                            row.querySelector('.quantity-input').value = '';
                            row.querySelector('.amount-input').value = '';
                        }
                    })
                    .catch(error => console.log(error));
            } else {
                row.querySelector('.category-select').value = '';
                row.querySelector('.img-preview').src = '';
                row.querySelector('.img-preview').style.display = 'none';
                row.querySelector('.quantity-input').value = '';
                row.querySelector('.amount-input').value = '';
            }
        }

        // Attach event listener for existing elements
        $(document).on("change", ".product-select", function() {
            handleProductSelect(this);
        });

        // Invoice Preview
        $('#previewInvoice').on('click', function() {
            const tableBody = $('#productTableBody');
            const invoiceTableBody = $('#invoiceTableBody');
            invoiceTableBody.empty(); // Clear previous rows

            let totalPrice = 0;

            tableBody.find('.product-row').each(function() {
                const imageSrc = $(this).find('.img-preview').attr('src');
                const productName = $(this).find('.product-select option:selected').text();
                const quantity = $(this).find('.quantity-input').val();
                const price = $(this).find('.amount-input').val();

                // Only add the row if all fields are filled
                if (imageSrc && productName && quantity && price) {
                    const row = `
                        <tr>
                            <td><img src="${imageSrc}" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover;"></td>
                            <td>${productName}</td>
                            <td>${quantity}</td>
                            <td>${price}</td>
                        </tr>
                    `;
                    invoiceTableBody.append(row);

                    // Calculate total price
                    totalPrice += parseFloat(price) * parseFloat(quantity);
                }
            });

            // Update the total price in the modal
            $('#totalPrice').text(`$${totalPrice.toFixed(2)}`);

            // If no valid rows were added, show a message
            if (invoiceTableBody.children().length === 0) {
                invoiceTableBody.append('<tr><td colspan="4">No products to preview. Please fill in all fields.</td></tr>');
                $('#totalPrice').text('$0.00');
            }
        });

    // Export to PDF
// Export to PDF
$('#exportPDF').on('click', function() {
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });

        // --- Header Section ---
        // Add logo placeholder (replace with actual logo URL if available)
        // doc.addImage('path/to/logo.png', 'PNG', 85, 10, 40, 20); // Centered logo
        doc.setFontSize(16);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(0, 0, 0);
        doc.text('Engrty Alone', 105, 20, { align: 'center' }); // Shop name (centered on A4: 210mm / 2 = 105mm)

        // Date and Time
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(0, 0, 0);
        const now = new Date();
        const dateTime = now.toLocaleString();
        doc.text(`Date: ${dateTime}`, 14, 30);

        // Address
        doc.text('Address: 36 Terrick Rd, Ellington PE18 2NT, United Kingdom', 14, 35);

        // Phone Number
        doc.text('Phone: 067 67 67 67', 14, 40);

        // --- Table Section ---
        const table = document.querySelector('#invoiceTableBody');
        if (!table || table.children.length === 0) {
            alert('No data to export. Please add products to the preview.');
            return;
        }

        // Prepare table data with correct column mapping
        const tableData = [];
        const imageData = []; // Store image sources separately
        let totalPrice = 0;

        $(table).find('tr').each(function(index) {
            const imageSrc = $(this).find('td:eq(0) img').attr('src');
            const productName = $(this).find('td:eq(1)').text();
            const quantity = parseFloat($(this).find('td:eq(2)').text());
            const price = parseFloat($(this).find('td:eq(3)').text());
            const amount = quantity * price;
            totalPrice += amount;

            // Store image source for rendering later
            imageData.push(imageSrc);

            // Map data to correct columns: Image placeholder, Product Name, Quantity, Price
            tableData.push([
                '', // Placeholder for image (will be drawn in didDrawCell)
                productName,
                quantity.toString(),
                price.toFixed(2)
            ]);
        });

        doc.autoTable({
            head: [['Image', 'Product Name', 'Quantity', 'Price ($)']],
            body: tableData,
            startY: 50,
            theme: 'grid',
            headStyles: {
                fillColor: [200, 200, 200], // Light gray header
                textColor: [0, 0, 0],
                fontSize: 10,
                fontStyle: 'bold'
            },
            bodyStyles: {
                fontSize: 10,
                textColor: [0, 0, 0],
                fillColor: [255, 255, 255] // White background for body
            },
            columnStyles: {
                0: { cellWidth: 30 }, // Image
                1: { cellWidth: 70, halign: 'left' }, // Product Name (left-aligned)
                2: { cellWidth: 40, halign: 'left' }, // Quantity (left-aligned)
                3: { cellWidth: 40, halign: 'left' }  // Price (left-aligned)
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
            },
            didParseCell: function(data) {
                // Ensure the Product Name column does not render the image
                if (data.column.index === 1) {
                    data.cell.text = data.cell.raw; // Ensure only the product name text is rendered
                }
            }
        });

        // --- Total Price Section ---
        const finalY = doc.lastAutoTable.finalY || 50;
        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(0, 0, 0);
        doc.text(`Total Price: $${totalPrice.toFixed(2)}`, 196, finalY + 10, { align: 'right' });

        // --- Thank You Message ---
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(0, 0, 0);
        doc.text('Thank you', 14, finalY + 20);

        // Save the PDF
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
    });

    function getProductDetails() {
        var productId = document.getElementById("product_name").value;

        if (productId) {
            // Send AJAX request to fetch product details
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "/inventory/getProductDetails?id=" + productId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);

                    // Handle the response and display the product details
                    if (response.error) {
                        document.getElementById("product_details").innerHTML = "Error: " + response.error;
                    } else {
                        document.getElementById("product_details").innerHTML = `
                        <p><strong>Product Name:</strong> ${response.product_name}</p>
                        <p><strong>Category:</strong> ${response.category_name}</p>
                        <p><strong>Price:</strong> $${response.amount}</p>
                        <p><strong>Quantity:</strong> ${response.quantity}</p>
                        <p><strong>Expiration Date:</strong> ${response.expiration_date}</p>
                    `;
                    }
                }
            };
            xhr.send();
        } else {
            document.getElementById("product_details").innerHTML = "";
        }
    }
</script>