<main class="main-content create-content position-relative max-height-vh-100 h-100">
    <h2 class="text-center head-add" style="padding-top: 20px;">Add stock product</h2>
    <div class="col-md-12 mt-5 mx-auto">
        <div class="card p-3" style="box-shadow: none;">
            <form id="productForm" action="/inventory/store" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="d-flex flex-row justify-content-start flex-wrap">
                    <!-- Product Image -->
                    <div class="form-group mb-3 col-12 col-md-2">
                        <label for="image">Product Image</label>
                        <input type="file" class="form-control image-add" id="image" name="image" accept="image/*" required>
                        <div class="invalid-feedback">Please upload a valid product image.</div>
                    </div>

                    <!-- Category Selection -->
                    <div class="form-group mb-3 col-12 col-md-2">
                        <label for="productCategory">Category</label>
                        <select id="productCategory" name="category_id" class="form-control" required>
            
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
                    </div>

                    <!-- Product Name -->
                    <div class="form-group mb-3 col-12 col-md-2">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                        <div class="invalid-feedback">Product name is required.</div>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group mb-3 col-12 col-md-2">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                        <div class="invalid-feedback">Quantity must be at least 1.</div>
                    </div>

                    <!-- Price -->
                    <div class="form-group mb-3 col-12 col-md-2">
                        <label for="amount">Price ($)</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" required>
                        <div class="invalid-feedback">Please enter a valid price.</div>
                    </div>

                    <!-- Expiration Date -->
                    <div class="form-group mb-4 col-12 col-md-2">
                        <label for="expiration_date">Expiration Date</label>
                        <input type="date" class="form-control w-100" id="expiration_date" name="expiration_date" required>
                        <div class="invalid-feedback">Please select a valid expiration date.</div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end align-items-center">
                    <button type="button" id="addMore" class="btn btn-primary">Add more</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>

            <!-- Display Saved Data (Dynamically Added) -->
            <div id="savedProducts" class="mt-5">
                <h4>Saved Products:</h4>
                <div id="productList" class="d-flex flex-column"></div>
            </div>
        </div>
    </div>
</main>


<script>
    document.addEventListener("DOMContentLoaded", function () {
    const productForm = document.getElementById('productForm');
    const addMoreButton = document.getElementById('addMore');
    const savedProductsContainer = document.getElementById('productList');

    // Retrieve stored products from localStorage
    const loadProducts = () => {
        const products = JSON.parse(localStorage.getItem('products')) || [];
        savedProductsContainer.innerHTML = '';
        
        products.forEach((product, index) => {
            const productRow = document.createElement('div');
            productRow.classList.add('d-flex', 'justify-content-between', 'mb-2');

            productRow.innerHTML = `
                <div class="col-2">${product.product_name}</div>
                <div class="col-2">${product.category}</div>
                <div class="col-2">${product.quantity}</div>
                <div class="col-2">${product.amount}</div>
                <div class="col-2">${product.expiration_date}</div>
                <div class="col-2">
                    <button class="btn btn-warning edit" data-index="${index}">Edit</button>
                    <button class="btn btn-danger remove" data-index="${index}">Remove</button>
                </div>
            `;
            savedProductsContainer.appendChild(productRow);
        });
    };

    // Add product to localStorage
    const addProductToLocalStorage = () => {
        const productName = document.getElementById('product_name').value;
        const category = document.getElementById('productCategory').value;
        const quantity = document.getElementById('quantity').value;
        const amount = document.getElementById('amount').value;
        const expirationDate = document.getElementById('expiration_date').value;
        
        if (!productName || !category || !quantity || !amount || !expirationDate) {
            alert('Please fill in all fields');
            return;
        }

        const newProduct = {
            product_name: productName,
            category: category,
            quantity: quantity,
            amount: amount,
            expiration_date: expirationDate
        };

        let products = JSON.parse(localStorage.getItem('products')) || [];
        products.push(newProduct);
        localStorage.setItem('products', JSON.stringify(products));

        loadProducts(); // Reload saved products
    };

    // Remove product from localStorage
    const removeProductFromLocalStorage = (index) => {
        let products = JSON.parse(localStorage.getItem('products')) || [];
        products.splice(index, 1);
        localStorage.setItem('products', JSON.stringify(products));

        loadProducts(); // Reload saved products
    };

    // Edit product in localStorage
    const editProductInLocalStorage = (index) => {
        let products = JSON.parse(localStorage.getItem('products')) || [];
        const product = products[index];

        // Pre-fill the form with the selected product's data
        document.getElementById('product_name').value = product.product_name;
        document.getElementById('productCategory').value = product.category;
        document.getElementById('quantity').value = product.quantity;
        document.getElementById('amount').value = product.amount;
        document.getElementById('expiration_date').value = product.expiration_date;

        // Optionally, remove the product before editing
        removeProductFromLocalStorage(index);
    };

    // Add More button handler
    addMoreButton.addEventListener('click', addProductToLocalStorage);

    // Handle the Remove and Edit buttons dynamically
    savedProductsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove')) {
            const index = e.target.getAttribute('data-index');
            removeProductFromLocalStorage(index);
        }

        if (e.target.classList.contains('edit')) {
            const index = e.target.getAttribute('data-index');
            editProductInLocalStorage(index);
        }
    });

    // Load products on page load
    loadProducts();
});

</script>