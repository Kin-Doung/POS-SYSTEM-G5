<?php require_once './views/layouts/side.php'; ?>

<main class="main-content create-content position-relative max-height-vh-100 h-200">
    <h2 class="text-center head-add" style="padding-top: 10px; font-size: 20px;">Add Stock Product</h2>
    <div class="col-md-12 mt-3 mx-auto">
        <div class="card p-2" style="box-shadow: none;">
            <form id="productForm" action="/inventory/store" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <table border="1" width="100%" style="border-collapse: collapse; font-size: 14px; text-align: center;">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Price ($)</th>
                            <th>Exp. Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        <!-- Dynamic rows will be added here -->
                    </tbody>
                    <!-- Form Row for New Product -->
                    <tr id="newProductRow">
                        <td><input type="file" id="image" name="image" accept="image/*" style="padding: 8px; height:38px; width: 100%"></td>
                        <td>
                        <div>

                        <select id="productCategory" name="category_id" required style="padding: 8px;">
                                <option value="">Select</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option disabled>No Categories</option>
                                <?php endif; ?>
                            </select> 
                        </div>


                            
                        </td>
                        <td><input type="text" id="product_name" name="product_name" required style="padding: 8px ;"></td>
                        <td><input type="number" id="quantity" name="quantity" min="1" required style="padding: 8px;"></td>
                        <td><input type="number" id="amount" name="amount" min="0" step="0.01" required style="padding: 8px;"></td>
                        <td><input type="date" id="expiration_date" name="expiration_date" required style="padding: 8px;"></td>
                    </tr>
                </table>

                <div class="d-flex justify-content-start mt-3">
                    <button type="button" id="addMoreBtn" style="padding: 8px 14px; background-color: blue; color: white;">Add more</button>
                </div>

                <div style="margin-top: 10px; text-align: right;">
                    <button type="submit" style="padding: 8px 18px; font-size: 14px; background-color: green; color: white; border: none; cursor: pointer;">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<style>
        body {
        font-family: Arial, sans-serif;
    }

    .head-add {
        color: darkblue;
        font-weight: bold;
    }

    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
        font-size: 14px;
    }

    th, td {
        text-align:center;
        border: 1px solid #ddd;
        padding: 0;
    }

    th {
        background-color: rgb(118, 127, 255);
        padding: 10px;
    }

    input,
    select {
        width: 100%;
        height: 100%;
        box-sizing: border-box;
        border: none;
        padding: 10px;
        background: transparent; /* Makes input feel like part of the table */
        font-size: 14px;
        background: #ddefff;
    }

    input:focus,
    select:focus {
        outline: none; /* Removes focus border */
        background-color: rgba(0, 0, 0, 0.05); /* Subtle highlight when selected */
    }

    button {
        padding: 5px 15px;
        font-size: 14px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    #addMoreBtn {
        background-color: blue;
        color: white;
    }

    #addMoreBtn:hover {
        background-color: darkblue;
    }

    button[type="submit"] {
        background-color: green;
        color: white;
    }

    button[type="submit"]:hover {
        background-color: darkgreen;
    }
    td {
    background: none;
    border: none;
    outline: none;
    cursor: pointer;
    font-size: 16px;
    padding: 5px;
    border-bottom: none;
}

td button i {
    color: #333; /* Default icon color */
    transition: color 0.3s ease;
}

td button:hover i {
    color: red; /* Change color on hover */
}



</style>

<script>
    const productStorageKey = 'products';
    let productCounter = 1;
    let editingProductId = null;

    window.onload = function() {
        loadProductsFromLocalStorage();
    };

    // Add more button to create a new product or update an existing one
    document.getElementById("addMoreBtn").addEventListener("click", function() {
        const image = document.getElementById("image").files[0];
        const category = document.getElementById("productCategory").value;
        const name = document.getElementById("product_name").value;
        const quantity = document.getElementById("quantity").value;
        const price = document.getElementById("amount").value;
        const expiration_date = document.getElementById("expiration_date").value;

        // Ensure all fields are filled
        if (category && name && quantity && price && expiration_date) {
            let newProduct = {
                id: editingProductId || productCounter++,
                category: category,
                name: name,
                quantity: quantity,
                price: price,
                expiration_date: expiration_date
            };

            // Handle image upload
            const reader = new FileReader();
            reader.onload = function(event) {
                newProduct.image = event.target.result;

                // If editing, update the product; else, add the new product
                if (editingProductId) {
                    updateProductInTable(newProduct);
                    updateProductInLocalStorage(newProduct);
                    editingProductId = null;
                    document.getElementById("addMoreBtn").textContent = "Add More";
                } else {
                    addRowToTable(newProduct);
                    saveProductToLocalStorage(newProduct);
                }

                clearForm();
            };

            if (image) {
                reader.readAsDataURL(image);
            } else {
                newProduct.image = '';
                if (editingProductId) {
                    updateProductInTable(newProduct);
                    updateProductInLocalStorageWithoutImage(newProduct);
                    editingProductId = null;
                    document.getElementById("addMoreBtn").textContent = "Add More";
                } else {
                    addRowToTable(newProduct);
                    saveProductToLocalStorage(newProduct);
                }
                clearForm();
            }
        } else {
            alert("Please fill out all fields before adding.");
        }
    });

    // Load products from localStorage when the page is loaded
    function loadProductsFromLocalStorage() {
        const products = JSON.parse(localStorage.getItem(productStorageKey)) || [];
        products.forEach(product => {
            addRowToTable(product);
        });
    }

    // Add a new row in the product table
    function addRowToTable(product) {
        const tableBody = document.getElementById("productTableBody");
        const row = document.createElement("tr");
        row.setAttribute("data-product-id", product.id);

        row.innerHTML = `
            <td><img src="${product.image || 'default_image.png'}" alt="Product Image" style="width: 40px; height:40px"></td>
            <td>${product.category}</td>
            <td>${product.name}</td>
            <td>${product.quantity}</td>
            <td>${product.price}</td>
            <td>${product.expiration_date}</td>
            <td style="display: flex; justify-content: center; gap: 10px;">
                <button type="button" onclick="editRow(${product.id})"> <i class="fa-solid fa-pen-to-square"></i></button>
                <button type="button" onclick="deleteRow(${product.id}, this)"><i class="fa-solid fa-trash"></i></button>
            </td>`;

        tableBody.appendChild(row);
    }

    // Edit the row of a product
    function editRow(productId) {
        const rows = document.getElementById("productTableBody").rows;
        let productToEdit;

        // Find the product by ID
        for (let row of rows) {
            if (row.getAttribute("data-product-id") == productId) {
                productToEdit = {
                    id: productId,
                    image: row.cells[0].children[0].src, // Preserve the current image
                    category: row.cells[1].textContent,
                    name: row.cells[2].textContent,
                    quantity: row.cells[3].textContent,
                    price: row.cells[4].textContent,
                    expiration_date: row.cells[5].textContent
                };
                break;
            }
        }

        // Populate form fields with the product data
        document.getElementById("productCategory").value = productToEdit.category;
        document.getElementById("product_name").value = productToEdit.name;
        document.getElementById("quantity").value = productToEdit.quantity;
        document.getElementById("amount").value = productToEdit.price; // Editable field
        document.getElementById("expiration_date").value = productToEdit.expiration_date;

        // Change button text and set editing mode
        document.getElementById("addMoreBtn").textContent = "Update";
        editingProductId = productToEdit.id;
    }

    // Update the product in the table after editing
    function updateProductInTable(updatedProduct) {
        const rows = document.getElementById("productTableBody").rows;
        for (let row of rows) {
            if (row.getAttribute("data-product-id") == updatedProduct.id) {
                // Only update the price and other fields (not the image)
                row.cells[0].children[0].src = updatedProduct.image || row.cells[0].children[0].src; // Keep the original image
                row.cells[1].textContent = updatedProduct.category;
                row.cells[2].textContent = updatedProduct.name;
                row.cells[3].textContent = updatedProduct.quantity;
                row.cells[4].textContent = updatedProduct.price; // Update price
                row.cells[5].textContent = updatedProduct.expiration_date;
            }
        }
    }

    // Function to save updated product details to localStorage
    function saveUpdatedProductToLocalStorage(updatedProduct) {
        let products = JSON.parse(localStorage.getItem(productStorageKey)) || [];
        const index = products.findIndex(product => product.id === updatedProduct.id);
        if (index !== -1) {
            // Update only the changed fields (e.g., price) in localStorage
            products[index].price = updatedProduct.price;
            products[index].quantity = updatedProduct.quantity;
            products[index].expiration_date = updatedProduct.expiration_date;
            localStorage.setItem(productStorageKey, JSON.stringify(products));
        }
    }

    // Delete a product row from the table and localStorage
    function deleteRow(productId, button) {
        const row = button.closest("tr"); // Find the row that contains the clicked button

        // Delete from localStorage
        deleteProductFromLocalStorage(productId);

        // Remove the row from the table
        row.remove();
    }

    // Clear the form after adding/updating a product
    function clearForm() {
        document.getElementById("productForm").reset();
    }

    // Save a new product to localStorage
    function saveProductToLocalStorage(product) {
        let products = JSON.parse(localStorage.getItem(productStorageKey)) || [];
        products.push(product);
        localStorage.setItem(productStorageKey, JSON.stringify(products));
    }

    // Update a product in localStorage
    function updateProductInLocalStorage(updatedProduct) {
        let products = JSON.parse(localStorage.getItem(productStorageKey)) || [];
        const index = products.findIndex(product => product.id === updatedProduct.id);
        if (index !== -1) {
            products[index] = updatedProduct;
            localStorage.setItem(productStorageKey, JSON.stringify(products));
        }
    }

    // Update a product in localStorage without the image (if no image is provided)
    function updateProductInLocalStorageWithoutImage(updatedProduct) {
        let products = JSON.parse(localStorage.getItem(productStorageKey)) || [];
        const index = products.findIndex(product => product.id === updatedProduct.id);
        if (index !== -1) {
            products[index] = updatedProduct;
            localStorage.setItem(productStorageKey, JSON.stringify(products));
        }
    }

    // Delete a product from localStorage
    function deleteProductFromLocalStorage(productId) {
        let products = JSON.parse(localStorage.getItem(productStorageKey)) || [];
        products = products.filter(product => product.id !== productId); // Remove the product with the matching ID
        localStorage.setItem(productStorageKey, JSON.stringify(products)); // Update localStorage
    }
</script>
