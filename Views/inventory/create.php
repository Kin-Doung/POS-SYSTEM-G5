<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="col-md-8 mt-5 mx-auto">
        <form action="/inventory/store" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <!-- Product Image -->
            <div class="form-group mb-3">
                <label for="image">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                <div class="invalid-feedback">Please upload a valid product image.</div>
            </div>

            <!-- Category Selection -->
            <div class="input-group mb-3">
                <select id="productCategory" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']); ?>">
                                <?= htmlspecialchars($category['name']); ?>
                            </option>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No categories available</option>
                    <?php endif; ?>
                </select><br />
            </div>

            <!-- Product Name -->
            <div class="form-group mb-3">
                <label for="product_name">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
                <div class="invalid-feedback">Product name is required.</div>
            </div>

            <!-- Quantity -->
            <div class="form-group mb-3">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                <div class="invalid-feedback">Quantity must be at least 1.</div>
            </div>

            <!-- Price -->
            <div class="form-group mb-3">
                <label for="amount">Price ($)</label>
                <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" required>
                <div class="invalid-feedback">Please enter a valid price.</div>
            </div>

            <!-- Expiration Date -->
            <div class="form-group mb-4">
                <label for="expiration_date">Expiration Date</label>
                <input type="date" class="form-control" id="expiration_date" name="expiration_date" required>
                <div class="invalid-feedback">Please select a valid expiration date.</div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100">Add Product</button>
        </form>

    </div>

</main>

<style>
    .head-add{
        font-family: "Poppins", sans-serif;
    }
    /* Custom width for the modal */
    #invoiceModal .modal-dialog {
        max-width: 1200px;
        /* Set your preferred width */
        width: 90%;
        /* You can adjust the width percentage if needed */
    }
    .btn-preview{
        background: orange !important;
    
    }
    .btn-export{
        background: orange;
        color: #fff;
        padding: 8px;
        border-radius: 5px;
        border: none;
    }
    .btn-export:hover{
        background: darkorange;
    }
    .btn-export-excel{
        background: green;
        color: #fff;
        padding: 8px;
        border: none;
        border-radius: 5px;
    }
    .btn-export-excel:hover{
        background-color: darkgreen;
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
    }



    .add-moree {
        padding: 7px 12px;
        border-radius: 50px;
        color: #fff;
        font-weight: bold;
        font-size: 18px;
        text-decoration: none;
        background: rgb(73, 73, 253);
        transition: background-color 0.3s ease;
        /* Smooth transition */
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
        border: none;
    }

    .add-moree:hover {
        color: #fff;
        background: rgb(2, 2, 221);
    }

    .btn-submit {
        padding: 7px 12px;
        border-radius: 50px;
        color: #fff;
        font-weight: bold;
        font-size: 18px;
        text-decoration: none;
        background: rgb(0, 151, 0);
        transition: background-color 0.3s ease;
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
        border: none;
    }

    .btn-submit:hover {
        color: #fff;
        background: rgb(0, 131, 0);

    }

    /* Default style for inputs without data */
    .empty-input {
        background-color: #fff;
        border: 1px solid #ced4da;
    }

    /* Style for inputs with data */
    .filled-input {
        background-color: transparent;
        border: none;
    }

    /* Adjust the height and width of the table rows and cells */
    table.table-bordered {
        width: 100%;
        table-layout: fixed;
        /* Ensures the table columns don't expand too wide */
    }

    /* Style for the table headers */
    table.table-bordered th {
        padding: 5px 10px;
        font-size: 14px;
        height: 40px;
        text-align: center;
        /* Center-align the text in the headers */
    }

    /* Style for the table cells */
    table.table-bordered td {
        padding: 5px 10px;
        font-size: 14px;
        height: 40px;
        text-align: center;
        vertical-align: middle;
    }

    /* Style for the table headers */
    table.table-bordered th {
        background: rgb(73, 73, 253);

        /* Change to any color you prefer */
        color: #ffffff;
        /* White text for contrast */
        padding: 10px;
        font-size: 14px;
        height: 40px;
        text-align: center;
        border: 1px solid #dee2e6;
    }


    /* Style for the input fields and selects */
    input.form-control,
    select.form-control {
        height: 30px;
        /* Set a smaller height for inputs */
        font-size: 14px;
        /* Smaller font size for inputs */
        text-align: center;
        /* Center-align the input text */
        padding: 0;
        /* Remove any extra padding */
        line-height: 30px;
        /* Vertically align text in the input */
        margin-top: 20px;
    }

    /* Style for the image preview */
    .img-preview {
        width: 30px;
        /* Reduce the width of the image preview */
        height: 30px;
        /* Reduce the height of the image preview */
        display: inline-block;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadSavedProducts(); // Load stored products when page loads
    });

    document.getElementById('addMore').addEventListener('click', function() {
        const productTableBody = document.getElementById('productTableBody');

        // Create a new row for the table
        const newRow = document.createElement('tr');
        newRow.classList.add('product-row');

        // Clone the first row
        const firstRow = document.querySelector('.product-row');
        newRow.innerHTML = firstRow.innerHTML;

        // Append new row
        productTableBody.appendChild(newRow);

        // Reset the image preview and input
        const newImageInput = newRow.querySelector('.image-add');
        const newImagePreview = newRow.querySelector('.img-preview');

        newImageInput.style.display = 'block';
        newImagePreview.style.display = 'none';
        newImageInput.value = '';

        // Apply empty input style to all inputs
        const newInputs = newRow.querySelectorAll('input, select');
        newInputs.forEach(input => {
            input.classList.add('empty-input');
            input.classList.remove('filled-input');
        });

        saveProductsToLocalStorage();
    });

    document.getElementById('productTableBody').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
            saveProductsToLocalStorage();
        }
    });

    document.getElementById('productTableBody').addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('image-add')) {
            const input = e.target;
            const row = input.closest('tr');
            const imagePreview = row.querySelector('.img-preview');

            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    imagePreview.style.display = 'inline';
                    input.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
            saveProductsToLocalStorage();
        }
    });

    document.getElementById('productTableBody').addEventListener('input', function(e) {
        const input = e.target;
        if (input.value.trim() !== '') {
            input.classList.add('filled-input');
            input.classList.remove('empty-input');
        } else {
            input.classList.add('empty-input');
            input.classList.remove('filled-input');
        }

        saveProductsToLocalStorage();
    });

    document.getElementById('productForm').addEventListener('submit', function() {
        localStorage.removeItem('savedProducts'); // Clear local storage on submit
    });

    // function saveProductsToLocalStorage() {
    //     const rows = document.querySelectorAll('.product-row');
    //     const products = [];

    //     rows.forEach(row => {
    //         const product = {
    //             category: row.querySelector('[name="category_id[]"]').value,
    //             name: row.querySelector('[name="product_name[]"]').value,
    //             quantity: row.querySelector('[name="quantity[]"]').value,
    //             price: row.querySelector('[name="amount[]"]').value,
    //             expiration: row.querySelector('[name="expiration_date[]"]').value
    //         };
    //         products.push(product);
    //     });

    //     localStorage.setItem('savedProducts', JSON.stringify(products));
    // }

    function loadSavedProducts() {
        const savedProducts = localStorage.getItem('savedProducts');
        if (!savedProducts) return;

        const products = JSON.parse(savedProducts);
        const productTableBody = document.getElementById('productTableBody');

        productTableBody.innerHTML = ''; // Clear table before adding saved products

        products.forEach(product => {
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
                        <option value="<?= htmlspecialchars($category['id']) ?>" 
                            ${product.category == <?= $category['id'] ?> ? 'selected' : ''}>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="text" class="form-control" name="product_name[]" value="${product.name}" required>
            </td>
            <td>
                <input type="number" class="form-control" name="quantity[]" min="1" value="${product.quantity}" required>
            </td>
            <td>
                <input type="number" class="form-control" name="amount[]" min="0" step="0.01" value="${product.price}" required>
            </td>
            <td>
                <input type="date" class="form-control w-100" name="expiration_date[]" value="${product.expiration}" required>
            </td>
          <!-- Inside the 'product-row' template -->
            <td>
                 <button type="button" class=" removeRow" style="background: none; border: none; color: red; font-size: 20px;">
                 <i class="fa-solid fa-trash"></i>
                 </button>
            </td>

        `;

            productTableBody.appendChild(newRow);

            // Apply the appropriate styles for filled or empty inputs
            const newInputs = newRow.querySelectorAll('input, select');
            newInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    input.classList.add('filled-input');
                    input.classList.remove('empty-input');
                } else {
                    input.classList.add('empty-input');
                    input.classList.remove('filled-input');
                }
            });
        });
    }
    // function loadSavedProducts() {
    //     const savedProducts = localStorage.getItem('savedProducts');
    //     if (!savedProducts) return;

    //     const products = JSON.parse(savedProducts);
    //     const productTableBody = document.getElementById('productTableBody');

    //     productTableBody.innerHTML = ''; // Clear table before adding saved products

    //     products.forEach(product => {
    //         const newRow = document.createElement('tr');
    //         newRow.classList.add('product-row');
    //         newRow.innerHTML = `
    //         <td>
    //             <input type="file" class="form-control image-add" name="image[]" accept="image/*" required>
    //             <img src="${product.image}" alt="Product Image" class="img-preview" style="display: inline; width: 50px; height: 50px;">
    //         </td>
    //         <td>
    //             <select name="category_id[]" class="form-control" required>
    //                 <option value="">Select Category</option>
    //                 ${product.category}
    //             </select>
    //         </td>
    //         <td>
    //             <input type="text" class="form-control" name="product_name[]" value="${product.name}" required>
    //         </td>
    //         <td>
    //             <input type="number" class="form-control" name="quantity[]" min="1" value="${product.quantity}" required>
    //         </td>
    //         <td>
    //             <input type="number" class="form-control" name="amount[]" min="0" step="0.01" value="${product.price}" required>
    //         </td>
    //         <td>
    //             <input type="date" class="form-control w-100" name="expiration_date[]" value="${product.expiration}" required>
    //         </td>
    //         <td>
    //             <button type="button" class="removeRow" style="background: none; border: none; color: red; font-size: 20px;">
    //                 <i class="fa-solid fa-trash"></i>
    //             </button>
    //         </td>
    //     `;
    //         productTableBody.appendChild(newRow);
    //     });
    // }

    // Function to capture the data from the input table
document.getElementById('previewInvoice').addEventListener('click', function() {
    const savedProducts = JSON.parse(localStorage.getItem('savedProducts')) || [];
    const invoiceTableBody = document.getElementById('invoiceTableBody');
    invoiceTableBody.innerHTML = ''; // Clear existing rows before adding new ones

    let totalPrice = 0;
    savedProducts.forEach(product => {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <img src="${product.image || ''}" class="img-preview" style="width: 50px; height: 50px;">
            </td>
            <td>${product.category}</td>
            <td>${product.name}</td>
            <td>${product.quantity}</td>
            <td>${product.price}</td>
            <td>${product.expiration}</td>
            <td>${(product.quantity * product.price).toFixed(2)}</td>
        `;
        invoiceTableBody.appendChild(newRow);
        totalPrice += product.quantity * product.price;
    });

    document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
});

// Update save function to store image base64 data
function saveProductsToLocalStorage() {
    const rows = document.querySelectorAll('.product-row');
    const products = [];

    rows.forEach(row => {
        const product = {
            category: row.querySelector('[name="category_id[]"]').value,
            name: row.querySelector('[name="product_name[]"]').value,
            quantity: row.querySelector('[name="quantity[]"]').value,
            price: row.querySelector('[name="amount[]"]').value,
            expiration: row.querySelector('[name="expiration_date[]"]').value,
            image: row.querySelector('.img-preview').src // Save the image source (base64 data)
        };
        products.push(product);
    });

    localStorage.setItem('savedProducts', JSON.stringify(products));
}

// Updated loadSavedProducts function to properly display the image
function loadSavedProducts() {
    const savedProducts = localStorage.getItem('savedProducts');
    if (!savedProducts) return;

    const products = JSON.parse(savedProducts);
    const productTableBody = document.getElementById('productTableBody');

    productTableBody.innerHTML = ''; // Clear table before adding saved products

    products.forEach(product => {
        const newRow = document.createElement('tr');
        newRow.classList.add('product-row');
        newRow.innerHTML = `
            <td>
                <input type="file" class="form-control image-add" name="image[]" accept="image/*">
                <img src="${product.image || ''}" alt="Product Image" class="img-preview" style="display: inline; width: 50px; height: 50px;">
            </td>
            <td>
                <select name="category_id[]" class="form-control" required>
                    <option value="">Select Category</option>
                    ${product.category}
                </select>
            </td>
            <td>
                <input type="text" class="form-control" name="product_name[]" value="${product.name}" required>
            </td>
            <td>
                <input type="number" class="form-control" name="quantity[]" min="1" value="${product.quantity}" required>
            </td>
            <td>
                <input type="number" class="form-control" name="amount[]" min="0" step="0.01" value="${product.price}" required>
            </td>
            <td>
                <input type="date" class="form-control w-100" name="expiration_date[]" value="${product.expiration}" required>
            </td>
            <td>
                <button type="button" class="removeRow" style="background: none; border: none; color: red; font-size: 20px;">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        `;
        productTableBody.appendChild(newRow);
    });
}


    // Function to capture the data from the input table
    // document.getElementById('previewInvoice').addEventListener('click', function() {
    //     const savedProducts = JSON.parse(localStorage.getItem('savedProducts')) || [];
    //     const invoiceTableBody = document.getElementById('invoiceTableBody');
    //     invoiceTableBody.innerHTML = ''; // Clear existing rows before adding new ones

    //     let totalPrice = 0;
    //     savedProducts.forEach(product => {
    //         const newRow = document.createElement('tr');
    //         newRow.innerHTML = `
    //         <td><img src="${product.image}" class="img-preview" style="width: 50px; height: 50px;"></td>
    //         <td>${product.category}</td>
    //         <td>${product.name}</td>
    //         <td>${product.quantity}</td>
    //         <td>${product.price}</td>
    //         <td>${product.expiration}</td>
    //         <td>${(product.quantity * product.price).toFixed(2)}</td>
    //     `;
    //         invoiceTableBody.appendChild(newRow);
    //         totalPrice += product.quantity * product.price;
    //     });

    //     document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
    // });




    document.getElementById('exportPDF').addEventListener('click', function() {
        const doc = new jsPDF();
        const table = document.getElementById('invoiceTableBody');
        const rows = table.querySelectorAll('tr');

        rows.forEach((row, index) => {
            const cols = row.querySelectorAll('td');
            const rowData = Array.from(cols).map(col => col.textContent.trim());
            doc.text(rowData.join(' | '), 10, 10 + (index * 10));
        });

        doc.save('invoice.pdf');
    });

    // Export to Excel functionality
    document.getElementById('exportExcel').addEventListener('click', function() {
        const ws = XLSX.utils.table_to_sheet(document.querySelector("#invoiceTableBody"));
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Invoice');

        // Save the Excel file
        XLSX.writeFile(wb, 'invoice.xlsx');
    });
</script>