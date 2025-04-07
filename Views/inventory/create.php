<?php
require_once './views/layouts/side.php';
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    .add-moree ,.btn-submit{
        border-radius: 50px;
        background: blue;
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
        color: #fff;
    }
    .add-moree:hover{
        background: darkblue;
    }
    .btn-submit{
        background: green;
    }
    .btn-submit:hover{
        background: green;
        color: #fff;
        box-shadow: none;
    }
    .btn-preview{
        background: orange;
        color: #fff;
        box-shadow: none;
    }
    .btn-preview:hover{
        background: orange;
        color: #fff;
        box-shadow: none;
    }

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<main class="main-content create-content position-relative max-height-vh-100 h-100">

    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>

    <!-- Body -->
    <div class="add-stock mr-2">
        <h2 class="text-center head-add" style="padding-top: 20px;">Add Stock Products</h2>
        <div class="d-flex justify-content-end align-item-center me-3">
            <button type="button" id="previewInvoice" class="btn btn-preview " data-bs-toggle="modal" data-bs-target="#invoiceModal">Preview Invoice</button>
        </div>
        <div class="col-md-12 mt-5mt-n3 mx-auto">
            <div class="card p-3" style="box-shadow: none;border:none">
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
                                        <!-- <input type="file" class="form-control image-add" name="image[]" accept="image/*"> -->
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
                                        <button type="button" class="btn removeRow" style="background: none; border: none; color: red; box-shadow:none;text-decoration:underline;font-size:15px;">
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