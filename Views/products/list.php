<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>
<style>
    /* Sidebar */
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #f8f9fa;
        padding: 20px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    /* Main Content */
    .main-content {
        margin-left: 250px;
        padding: 20px;
        position: relative;
        z-index: 1;
        min-height: 100vh;
        transition: margin-right 0.3s ease;
        margin-top: 70px;
        /* Offset for fixed navbar */
    }

    .cart-visible .main-content {
        margin-right: 350px;
        /* Adjust for cart width */
    }

    .navbar {
        width: 81.5%;
        position: sticky;
        top: 0;
        left: 280px;
        background: #fff;
    }

    .cart-icon {
        position: relative;
        cursor: pointer;
    }

    .cart-count {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }

    /* Container */
    .container-fluid {
        width: 100%;
        padding: 0 5px;
        /* Reduced for closer cards */
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -5px;
    }

    /* Product Section */
    .product-section {
        width: 100%;
        padding: 0 5px;
        margin-top: -70px;
    }

    /* Product Card */
    .product-col {
        width: 25%;
        /* 4 per row by default */
        padding: 0 5px;
        margin-bottom: 10px;
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .product-col.hidden {
        opacity: 0;
        pointer-events: none;
    }

    .cart-visible .product-col {
        width: 33.33%;
        /* 3 cards per row when cart is visible */
    }

    .product-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        height: 100%;
        transition: transform 0.2s ease;
    }

    .product-card:hover {
        transform: scale(1.04);
    }

    .image-wrapper {
        position: relative;
        padding-top: 70%;
        background-color: #f8f9fa;
        overflow: hidden;
    }

    .image-wrapper img {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 80%;
        height: 80%;
        transform: translate(-50%, -50%);
        object-fit: contain;
    }

    .card-body {
        padding: 5px;
        text-align: center;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .price {
        font-size: 1.1rem;
        color: #28a745;
        margin-bottom: 3px;
    }

    .quantity {
        color: #666;
        margin-bottom: 5px;
    }

    .buy {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 6px 12px;
        border-radius: 5px;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .buy:hover {
        background-color: #0056b3;
    }

    /* Cart Section */
    .cart-section {
        position: fixed;
        top: 70px;
        /* Below fixed navbar */
        right: 0;
        width: 350px;
        height: calc(100vh - 70px);
        padding: 15px;
        z-index: 900;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        background-color: #fff;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .cart-section.visible {
        transform: translateX(0);
    }

    .cart-card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .cart-header {
        background-color: #343a40;
        color: #fff;
        padding: 10px;
        text-align: center;
        position: relative;
    }

    .close-cart {
        position: absolute;
        right: 10px;
        top: 10px;
        background: none;
        border: none;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
    }

    .cart-body {
        padding: 15px;
        flex-grow: 1;
        overflow-y: auto;
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
        /* Simpler, no gaps */
        background-color: #fff;
        /* Clean white background */
    }

    .cart-table th {
        background-color: #f1f1f1;
        /* Light gray header */
        color: #333;
        /* Darker text */
        padding: 8px;
        /* Reduced padding */
        font-weight: 500;
        /* Lighter weight */
        font-size: 0.9rem;
        text-align: left;
        /* Simple alignment */
    }

    .cart-table td {
        padding: 8px;
        /* Consistent padding */
        border-bottom: 1px solid #eee;
        /* Subtle border */
        text-align: left;
        /* Simpler alignment */
    }

    .cart-table tr:last-child td {
        border-bottom: none;
        /* Clean finish */
    }

    .cart-qty,
    .cart-price {
        width: 60px;
        /* Smaller for simplicity */
        padding: 4px;
        border: 1px solid #ddd;
        border-radius: 3px;
        text-align: center;
        font-size: 0.9rem;
        background-color: #fff;
    }

    .cart-footer {
        padding: 15px;
        background-color: #f8f9fa;
        text-align: center;
        border-top: 1px solid #ddd;
    }

    .cart-btn {
        padding: 10px 20px;
        /* Larger for easy clicking */
        border: none;
        border-radius: 5px;
        color: #fff;
        margin: 5px;
        /* More spacing */
        cursor: pointer;
        font-size: 1rem;
        /* Larger text */
        width: 120px;
        /* Consistent size */
        transition: background-color 0.2s ease, transform 0.1s ease;
        /* Smooth interaction */
    }

    .cart-btn:hover {
        transform: scale(1.05);
        /* Slight hover effect */
    }

    .cart-btn:active {
        transform: scale(0.98);
        /* Press effect */
    }

    .cart-btn-success {
        background-color: #28a745;
    }

    .cart-btn-success:hover {
        background-color: #218838;
    }

    .cart-btn-info {
        background-color: #17a2b8;
    }

    .cart-btn-info:hover {
        background-color: #138496;
    }

    .cart-btn-secondary {
        background-color: #6c757d;
    }

    .cart-btn-secondary:hover {
        background-color: #5a6268;
    }

    .cart-btn-danger {
        background-color: #dc3545;
    }

    .cart-btn-danger:hover {
        background-color: #c82333;
    }

    

    /* Responsive Adjustments */
    @media (max-width: 767px) {
        .product-col {
            width: 50%;
        }

        .cart-section {
            width: 100%;
            top: 70px;
            height: calc(100vh - 70px);
        }

        .cart-visible .main-content {
            margin-right: 0;
        }
    }

    @media (max-width: 576px) {
        .product-col {
            width: 100%;
        }
    }
</style>
<nav class="navbar">
    <div class="search-container">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search...">
    </div>
    <div class="icons">
        <i class="fas fa-globe icon-btn"></i>
        <div class="icon-btn" id="notification-icon">
            <i class="fas fa-bell"></i>
            <span class="notification-badge" id="notification-count">8</span>
        </div>
        <div class="cart-icon" id="cartToggle">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count" id="cartCount">0</span>
        </div>
    </div>
    <div class="profile">
        <img src="../../views/assets/images/image.png" alt="User">
        <div class="profile-info">
            <span id="profile-name">Eng Ly</span>
            <span class="store-name" id="store-name">Owner Store</span>
        </div>
        <ul class="menu" id="menu">
            <li><a href="/settings" class="item">Account</a></li>
            <li><a href="/settings" class="item">Setting</a></li>
            <li><a href="/logout" class="item">Logout</a></li>
        </ul>
        <link rel="stylesheet" href="../../views/assets/css/settings/list.css">
        <script src="../../views/assets/js/setting.js"></script>
    </div>
</nav>
<main class="main-content">

    <div class="container-fluid">
        <div class="row" id="productRow">
            <!-- Product List Section -->
            <div class="product-section">
                <h3 class="mb-3 text-dark">Order Products</h3>
                <div class="row">
                    <?php foreach ($inventory as $item): ?>
                        <div class="product-col">
                            <div class="product-card">
                                <div class="image-wrapper">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?= htmlspecialchars($item['image']) ?>"
                                            alt="<?= htmlspecialchars($item['inventory_product_name']) ?>"
                                            onerror="this.src='/views/assets/images/default-product.jpg'">
                                    <?php else: ?>
                                        <img src="/views/assets/images/default-product.jpg"
                                            alt="Default Product Image">
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($item['inventory_product_name']) ?></h6>
                                    <p class="price" data-id="<?= htmlspecialchars($item['inventory_id']) ?>">
                                        $<?= htmlspecialchars($item['amount']) ?>
                                    </p>
                                    <p class="quantity" data-id="<?= htmlspecialchars($item['inventory_id']) ?>" style="display: none;">
                                        Qty: <?= htmlspecialchars($item['quantity']) ?>
                                    </p>
                                    <input type="hidden" name="inventory_id" value="<?= htmlspecialchars($item['inventory_id']) ?>" />
                                    <button class="buy">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- Cart Section -->
        <div class="cart-section" id="cartSection">
            <div class="cart-card">
                <div class="cart-header">
                    <h4>POS Terminal</h4>
                    <button class="close-cart" id="closeCart">✖</button>
                </div>
                <div class="cart-body">
                    <table class="cart-table" id="cartTable">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price ($)</th>
                            </tr>
                        </thead>
                        <tbody id="cartBody"></tbody>
                    </table>
                    <div style="text-align: right; margin-top: 15px;">
                        <h5 style="font-weight: bold;">Total: $<span id="grandTotal">0.00</span></h5>
                    </div>
                </div>
                <div class="cart-footer">
    <button class="cart-btn cart-btn-info" id="savePdf">Save PDF</button>
    <button class="cart-btn cart-btn-danger" id="clearCart">Clear</button>
    <button class="cart-btn cart-btn-primary" id="completeCart">Complete</button>
    <button class="cart-btn cart-btn-success" id="submitCart">Checkout</button>
</div>

<div id="qr-container" style="display: none;">
    <img id="qr-code-img" src="../assets/images/QR-code.png" alt="QR Code" style="width: 200px; height: 200px; margin-bottom: 15px;" />
    <input type="text" id="inputField" placeholder="Enter your details" />
</div>

            </div>
        </div>
    </div>
    <?php require_once 'views/layouts/footer.php'; ?>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    const cartSection = document.getElementById('cartSection');
    const cartToggle = document.getElementById('cartToggle');
    const closeCart = document.getElementById('closeCart');
    const cartCount = document.getElementById('cartCount');

    function showCart() {
        cartSection.classList.add('visible');
        document.body.classList.add('cart-visible');
    }

    function hideCart() {
        cartSection.classList.remove('visible');
        document.body.classList.remove('cart-visible');
    }

    function updateCard(inventoryId, newQuantity) {
        const qtyElement = document.querySelector(`.quantity[data-id="${inventoryId}"]`);
        if (qtyElement) qtyElement.textContent = `Qty: ${newQuantity}`;
    }

    function updateGrandTotal() {
        const total = Array.from(document.querySelectorAll('#cartBody tr'))
            .reduce((sum, row) => {
                const qty = parseInt(row.querySelector('.cart-qty').value) || 1;
                const price = parseFloat(row.querySelector('.cart-price').value) || 0;
                return sum + (qty * price);
            }, 0);
        document.getElementById('grandTotal').textContent = total.toFixed(2);
    }

    function updateCartCount() {
        const itemCount = document.querySelectorAll('#cartBody tr').length;
        cartCount.textContent = itemCount;
    }

    cartToggle.addEventListener('click', () => {
        if (cartSection.classList.contains('visible')) {
            hideCart();
        } else {
            showCart();
        }
    });

    closeCart.addEventListener('click', hideCart);

    document.querySelectorAll('.buy').forEach(button => {
        button.addEventListener('click', async function() {
            const card = this.closest('.product-card');
            const inventoryId = card.querySelector('input[name="inventory_id"]').value;
            const productName = card.querySelector('.card-title').textContent.trim();
            const price = parseFloat(card.querySelector('.price').textContent.replace('$', ''));
            const quantity = parseInt(card.querySelector('.quantity').textContent.replace('Qty: ', ''));
            const existingRow = document.querySelector(`#cartBody tr[data-id="${inventoryId}"]`);
            if (existingRow) {
                const qtyInput = existingRow.querySelector('.cart-qty');
                qtyInput.value = parseInt(qtyInput.value) + 1;
                qtyInput.dispatchEvent(new Event('input'));
                updateCartCount();
                showCart();
                return;
            }
            try {
                const response = await fetch('/products/syncQuantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        inventoryId,
                        quantity
                    })
                });
                if (!response.ok) throw new Error(`Server error: ${response.status}`);
                const data = await response.json();
                if (data.success) {
                    const row = document.createElement('tr');
                    row.dataset.id = inventoryId;
                    row.innerHTML = `
                        <td style="vertical-align: middle;">${productName}</td>
                        <td><input type="number" class="cart-qty" min="1" max="${quantity}" value="1"></td>
                        <td><input type="number" class="cart-price" min="0" step="0.01" value="${price.toFixed(2)}"></td>
                    `;
                    document.getElementById('cartBody').appendChild(row);
                    updateGrandTotal();
                    updateCartCount();
                    showCart();
                } else {
                    alert(`Error syncing quantity: ${data.message}`);
                }
            } catch (error) {
                console.error('Add to cart failed:', error);
                alert(`Failed to add item: ${error.message}`);
            }
        });
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('cart-qty') || e.target.classList.contains('cart-price')) {
            updateGrandTotal();
            updateCartCount();
        }
    });

    document.getElementById('submitCart').addEventListener('click', function() {
        const cartItems = [];
        let valid = true;
        document.querySelectorAll('#cartBody tr').forEach(row => {
            const inventoryId = row.dataset.id;
            const quantityInput = row.querySelector('.cart-qty');
            const priceInput = row.querySelector('.cart-price');
            const quantity = parseInt(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const maxQty = parseInt(quantityInput.max);
            if (quantity > 0) {
                if (quantity > maxQty) {
                    alert(`Quantity for ${row.cells[0].textContent} exceeds available stock (${maxQty})`);
                    valid = false;
                    return;
                }
                cartItems.push({
                    inventoryId,
                    quantity,
                    price
                });
            }
        });
        if (!valid || cartItems.length === 0) {
            if (cartItems.length === 0) alert('Cart is empty! Please add items to proceed.');
            return;
        }
        this.disabled = true;
        this.textContent = 'Processing...';
        fetch('/products/submitCart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cartItems
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Server error: ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    cartItems.forEach(item => {
                        const qtyElement = document.querySelector(`.quantity[data-id="${item.inventoryId}"]`);
                        const currentQty = parseInt(qtyElement.textContent.replace('Qty: ', ''));
                        updateCard(item.inventoryId, currentQty - item.quantity);
                    });
                    document.getElementById('cartBody').innerHTML = '';
                    hideCart();
                    updateCartCount();
                    alert('Checkout completed successfully! Inventory updated.');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to submit cart: ' + error.message);
            })
            .finally(() => {
                this.disabled = false;
                this.textContent = 'Checkout';
            });
    });

    document.getElementById('clearCart').addEventListener('click', function() {
        if (confirm('Are you sure you want to clear the cart?')) {
            document.getElementById('cartBody').innerHTML = '';
            document.getElementById('grandTotal').textContent = '0.00';
            updateCartCount();
            hideCart();
        }
    });

    const {
        jsPDF
    } = window.jspdf;
    document.getElementById('savePdf').addEventListener('click', function() {
        const doc = new jsPDF();
        doc.setFontSize(18);
        doc.text('Store Name POS', 105, 15, {
            align: 'center'
        });
        doc.setFontSize(10);
        doc.text('123 Business Ave, City, ST 12345', 105, 23, {
            align: 'center'
        });
        doc.text(`Date: ${new Date().toLocaleString()}`, 105, 31, {
            align: 'center'
        });
        doc.line(10, 35, 200, 35);
        let y = 45;
        doc.setFontSize(12);
        doc.text('Item', 10, y);
        doc.text('Qty', 100, y, {
            align: 'right'
        });
        doc.text('Price', 140, y, {
            align: 'right'
        });
        doc.text('Total', 180, y, {
            align: 'right'
        });
        y += 5;
        doc.line(10, y, 200, y);
        y += 5;
        document.querySelectorAll('#cartBody tr').forEach(row => {
            const product = row.cells[0].textContent.trim().substring(0, 20);
            const qty = row.querySelector('.cart-qty').value;
            const price = parseFloat(row.querySelector('.cart-price').value).toFixed(2);
            const total = (qty * price).toFixed(2);
            doc.text(product, 10, y);
            doc.text(qty, 100, y, {
                align: 'right'
            });
            doc.text(`$${price}`, 140, y, {
                align: 'right'
            });
            doc.text(`$${total}`, 180, y, {
                align: 'right'
            });
            y += 10;
        });
        doc.line(10, y, 200, y);
        y += 10;
        const grandTotal = document.getElementById('grandTotal').textContent;
        doc.setFontSize(14);
        doc.text(`Grand Total: $${grandTotal}`, 180, y, {
            align: 'right'
        });
        y += 10;
        doc.setFontSize(10);
        doc.text('Thank you for shopping with us!', 105, y, {
            align: 'center'
        });
        doc.save('pos-receipt.pdf');
    });


    // Search functionality with animation
    const searchInput = document.querySelector('.search-container input');
    const productCards = document.querySelectorAll('.product-col');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim().toLowerCase();

        productCards.forEach(card => {
            const productName = card.querySelector('.card-title').textContent.trim().toLowerCase();

            // Toggle hidden class for animation
            if (productName.includes(searchTerm)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    });

    document.getElementById('completeCart').addEventListener('click', function() {
    // Show QR code image and input field below the cart
    document.getElementById('qr-container').style.display = 'block';

    // Set the QR code image source (replace with your QR code image path)
    var qrCodeImageUrl = "path/to/your/qr-code-image.png";
    document.getElementById('qr-code-img').src = qrCodeImageUrl;
});

</script>