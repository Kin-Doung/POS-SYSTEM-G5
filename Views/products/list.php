<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>
    <!-- End Navbar -->

    <!-- Search and Category Filter -->
    <div class="input-group">
        <input type="text" id="searchInput" class="form-control input-group-search" placeholder="Search...">
        <select id="categorySelect" class="ms-2 selected">
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

    <!-- Product Card Section -->
    <div class="container mt-5 d-flex">
        <div class="product-list flex-grow-1">
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-6 col-sm-4 col-md-3 mb-4">
                        <div class="card square-card">
                            <div class="image-wrapper">
                                <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="card-body">
                                <h6 class="card-title text-center"><?= htmlspecialchars($product['name']) ?></h6>
                                <p class="card-text text-center price"><?= htmlspecialchars($product['price']) ?> $</p>
                                <div class="text-center mt-2">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>" />
                                    <button class="buy">Buy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="cart-section ms-4 p-3 border rounded shadow bg-white" id="cartSection" style="width: 500px; display: none;">
            <h4>Cart</h4>
            <table class="table table-bordered text-center" id="cartTable">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Total ($)</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <h5>Total: <span id="grandTotal">0</span> $</h5>
            <div class="text-center mt-3">
                <button class="btn btn-success" onclick="replaceCartInDatabase()">Submit</button>
            </div>
        </div>
    </div>

    <!--  -->

    <!-- JavaScript -->
    <script>
        function replaceCartInDatabase() {
            const cartTable = document.getElementById('cartTable').querySelector('tbody');
            const cartItems = [];
            cartTable.querySelectorAll('tr').forEach(row => {
                const productId = row.querySelector('input[name="product_id"]').value;
                const quantity = parseInt(row.querySelector('td:nth-child(2)').textContent); // Qty column
                cartItems.push({ product_id: productId, quantity: quantity });
            });

            fetch('/product/submitCart', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(cartItems)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('okay'); // Show "okay" on successful submission
                } else {
                    alert('Error: ' + data.message); // Show error message if it fails
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>