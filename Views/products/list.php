<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<!-- Add Font Awesome for the ellipsis icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
    }

    .cart-visible .main-content {
        margin-right: 350px;
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
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }

    .cart-count.visible {
        display: flex;
    }

    .container-fluid {
        width: 100%;
        padding: 0 5px;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -5px;
    }

    .product-section {
        width: 100%;
        padding: 0 5px;
        margin-top: -70px;
    }

    #submitCart {
        padding: 10px;
    }

    #moreOptionsBtn {
        padding: 10px;
    }

    #moreOptionsBtn::after {
        content: "\f0d7";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        margin-left: 8px;
    }

    #moreOptionsBtn.active::after {
        content: "\f0d8";
    }


    .product-col {
        width: 25%;
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
    }

    .product-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        height: 100%;
        transition: transform 0.2s ease;
        position: relative;
        /* Added for kebab menu positioning */
        z-index: 1;
        /* Base z-index for cards */
    }

    .product-card:hover {
        box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
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

    /* New Kebab Menu Styles */
    .kebab-menu {
        position: absolute;
        top: 5px;
        right: 5px;
        cursor: pointer;
        font-size: 20px;
        padding: 5px;
        z-index: 1001;
    }

    .kebab-menu:hover {
        color: #333;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 30px;
        right: 5px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        padding: 5px 0;
        z-index: 1002;
        /* Highest z-index to ensure it's in front */
    }

    .dropdown-menu.visible {
        display: block;
    }

    .dropdown-menu {
        width: 100%;
        padding: 8px 15px;
        border: none;
        background: none;
        text-align: left;
        cursor: pointer;
        color: #dc3545;
        font-size: 14px;
    }

    .dropdown-menu:hover {
        background-color: #f8f9fa;
    }

    .cart-section {
        position: fixed;
        top: 70px;
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
        background-color: #fff;
    }

    .cart-table th {
        background-color: #f1f1f1;
        color: #333;
        padding: 8px;
        font-weight: 500;
        font-size: 0.9rem;
        text-align: left;
    }

    .cart-table td {
        padding: 8px;
        text-align: left;
        vertical-align: middle;
    }

    .remove-item {
        cursor: pointer;
        color: #dc3545;
        font-size: 16px;
    }

    .remove-item:hover {
        color: #c82333;
    }

    .cart-qty,
    .cart-price {
        width: 60px;
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
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .cart-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.2s ease, transform 0.1s ease;
        width: 50%;
        max-width: 150px;
        font-size: 14px;
    }

    .cart-btn-success {
        padding: 12px 24px;
        max-width: 220px;
        background-color: #28a745;
    }

    .cart-btn-success:hover {
        background-color: #218838;
    }

    .more-options {
        position: relative;
        width: 100%;
        max-width: 200px;
    }

    .cart-btn-secondary {
        background-color: #6c757d;
        max-width: 100%;
        width: 160px;
    }

    .cart-btn-secondary:hover {
        background-color: #5a6268;
    }

    .options-dropdown {
        display: none;
        position: absolute;
        bottom: calc(100% + 5px);
        left: 50%;
        transform: translateX(-50%);
        background-color: #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        border-radius: 6px;
        z-index: 1000;
        width: 100%;
        max-width: 200px;
        flex-direction: column;
        padding: 5px;
        border: 1px solid #eee;
    }

    .options-dropdown.visible {
        display: flex;
    }

    .options-dropdown button {
        width: 100%;
        border: none;
        margin: 2px 0;
        padding: 12px;
        font-size: 0.95rem;
        color: #fff;
        text-align: center;
        transition: all 0.2s ease;
        border-radius: 4px;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .options-dropdown button:hover {
        transform: translateY(-1px);
        opacity: 0.95;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .options-dropdown button:active {
        transform: translateY(1px);
    }

    .cart-btn-info {
        background-color: #17a2b8;

    }

    .cart-btn-info:hover {
        background-color: #138496;
    }

    .cart-btn-danger {
        background-color: #dc3545;
    }

    .cart-btn-danger:hover {
        background-color: #c82333;
    }

    .cart-btn-primary {
        background-color: #007bff;
    }

    .cart-btn-primary:hover {
        background-color: #0056b3;
    }

    .cart-btn:hover {
        transform: scale(1.05);
    }

    #qr-container {
        margin-top: 15px;
        text-align: center;
    }

    #qr-container .cart-btn {
        margin: 5px auto;
    }

    #qr-container #inputField {
        margin-bottom: 15px;
        padding: 8px;
        width: 100%;
        max-width: 200px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    #cartToggle {
        font-size: 20px;
    }

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

        .cart-btn {
            max-width: 100%;
        }

        .cart-btn-success {
            padding: 12px 24px;
            font-size: 1rem;
            max-width: 100%;
        }

        .more-options {
            max-width: 100%;
        }

        .options-dropdown {
            max-width: 100%;
            left: 0;
            transform: none;
        }

        .options-dropdown button {
            padding: 12px;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .product-col {
            width: 100%;
        }
    }
    h3{
        font-family: "Poppins", sans-serif;
        font-weight: bold;
        margin-bottom: 10px;
        color: #000;
    }
</style>

<?php require_once './views/layouts/nav.php' ?>


<main class="main-content">
    <div class="container-fluid">
        <div class="row" id="productRow">
            <div class="product-section">
                <h3>Order Products</h3>
                <div class="row">
                    <?php foreach ($inventory as $item): ?>
                        <div class="product-col">
                            <div class="product-card">
                                <!-- Add Font Awesome ellipsis icon -->
                                <i class="fa-solid fa-ellipsis-vertical kebab-menu"></i>
                                <div class="dropdown-menu">
                                    <p class="delete-btn" data-id="<?= htmlspecialchars($item['inventory_id']) ?>"><i class="fa-solid fa-trash"></i></p>
                                </div>

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
        <div class="cart-section" id="cartSection">
            <div class="cart-card">
                <div class="cart-header">
                    <h4>POS Payout</h4>
                    <button class="close-cart" id="closeCart">✖</button>
                </div>
                <div class="cart-body">
                    <table class="cart-table" id="cartTable">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price ($)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cartBody"></tbody>
                    </table>
                    <div style="text-align: right; margin-top: 15px;">
                        <h5 style="font-weight: bold;">Total: $<span id="grandTotal">0.00</span></h5>
                    </div>
                    <div id="qr-container" style="display: none;">
                        <img id="qr-code-img" src="../../views/assets/images/QR-code.png" alt="QR Code" style="width: 80px; height: 80px; margin-bottom: 15px;"/>
                        <input type="text" id="inputField" placeholder="Enter your details" />
                    </div>
                </div>
                <div class="cart-footer">
                    <div class="more-options" id="moreOptionsContainer">
                        <button class="cart-btn cart-btn-secondary" id="moreOptionsBtn">More Options</button>
                        <div class="options-dropdown" id="optionsDropdown">
                            <button class="cart-btn cart-btn-info" id="savePdf">Save PDF</button>
                            <button class="cart-btn cart-btn-primary" id="completeCart">Payout</button>
                            <button class="cart-btn cart-btn-danger" id="clearCart">Clear</button>
                        </div>
                    </div>
                    <button class="cart-btn cart-btn-success" id="submitCart">Complete order</button>
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

    const TELEGRAM_BOT_TOKEN = '7914523767:AAEJxRARlS6nn4Qggt3lw8pOYWKdjAT3FaY';
    const TELEGRAM_CHAT_ID = '@engly_system_telegram';

    document.addEventListener('DOMContentLoaded', function() {
        loadCartFromLocalStorage();
    });

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
        return total;
    }

    function updateCartCount() {
        const itemCount = document.querySelectorAll('#cartBody tr').length;
        const cartCountElement = document.getElementById('cartCount');
        cartCountElement.textContent = itemCount;
        if (itemCount > 0) {
            cartCountElement.classList.add('visible');
        } else {
            cartCountElement.classList.remove('visible');
        }
        updateGrandTotal();
    }

    function saveCartToLocalStorage() {
        const cartItems = [];
        document.querySelectorAll('#cartBody tr').forEach(row => {
            cartItems.push({
                inventoryId: row.dataset.id,
                productName: row.cells[0].textContent,
                quantity: parseInt(row.querySelector('.cart-qty').value),
                price: parseFloat(row.querySelector('.cart-price').value),
                maxQty: parseInt(row.querySelector('.cart-qty').max)
            });
        });
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
    }

    function loadCartFromLocalStorage() {
        const savedItems = localStorage.getItem('cartItems');
        if (savedItems) {
            const cartItems = JSON.parse(savedItems);
            cartItems.forEach(item => {
                const row = document.createElement('tr');
                row.dataset.id = item.inventoryId;
                row.innerHTML = `
                    <td style="vertical-align: middle;">${item.productName}</td>
                    <td><input type="number" class="cart-qty" min="1" max="${item.maxQty}" value="${item.quantity}"></td>
                    <td><input type="number" class="cart-price" min="0" step="0.01" value="${item.price.toFixed(2)}"></td>
                    <td><span class="remove-item">✖</span></td>
                `;
                document.getElementById('cartBody').appendChild(row);
            });
            updateCartCount();
        }
    }

    async function sendToTelegram(message) {
        const url = `https://api.telegram.org/bot${TELEGRAM_BOT_TOKEN}/sendMessage`;
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    chat_id: TELEGRAM_CHAT_ID,
                    text: message,
                    parse_mode: 'Markdown'
                })
            });
            const data = await response.json();
            if (!data.ok) {
                throw new Error(`Telegram API error: ${data.description}`);
            }
            return true;
        } catch (error) {
            console.error('Failed to send message to Telegram:', error);
            return false;
        }
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
                saveCartToLocalStorage();
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
                        <td><span class="remove-item">✖</span></td>
                    `;
                    document.getElementById('cartBody').appendChild(row);
                    updateCartCount();
                    saveCartToLocalStorage();
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

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('tr').remove();
            updateCartCount();
            saveCartToLocalStorage();
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('cart-qty') || e.target.classList.contains('cart-price')) {
            updateGrandTotal();
            saveCartToLocalStorage();
        }
    });

    document.getElementById('submitCart').addEventListener('click', async function() {
        const cartItems = [];
        let valid = true;
        const itemsData = [];

        document.querySelectorAll('#cartBody tr').forEach(row => {
            const inventoryId = row.dataset.id;
            const productName = row.cells[0].textContent.trim();
            const quantityInput = row.querySelector('.cart-qty');
            const priceInput = row.querySelector('.cart-price');
            const quantity = parseInt(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const maxQty = parseInt(quantityInput.max);

            if (quantity > 0) {
                if (quantity > maxQty) {
                    alert(`Quantity for ${productName} exceeds available stock (${maxQty})`);
                    valid = false;
                    return;
                }
                cartItems.push({
                    inventoryId,
                    quantity,
                    price
                });
                itemsData.push({
                    productName,
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

        const now = new Date();
        const date = now.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        }).split('/').join('/');
        const time = now.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });

        let telegramMessage = `*Engly_Store Receipt*\n\n`;
        telegramMessage += `Date: ${date}\n`;
        telegramMessage += `Time: ${time}\n\n`;
        telegramMessage += "```\n";
        telegramMessage += `item             Qty    Price       Total\n`;
        telegramMessage += `-------------------------------------\n`;

        itemsData.forEach(item => {
            const productName = item.productName.padEnd(16, ' ').substring(0, 16);
            const quantity = `x${item.quantity}`.padEnd(6, ' ');
            const price = `$${item.price.toFixed(2)}`.padEnd(10, ' ');
            const total = `= $${(item.quantity * item.price).toFixed(2)}`;
            telegramMessage += `${productName} ${quantity} ${price} ${total}\n`;
        });

        telegramMessage += `-------------------------------------\n`;
        const total = updateGrandTotal();
        const totalPrice = `$${total.toFixed(2)}`;
        telegramMessage += `Total: ${' '.repeat(30 - totalPrice.length)}${totalPrice}\n`;
        telegramMessage += `-------------------------------------\n`;
        telegramMessage += "```\n";
        telegramMessage += `✅ Thank you!`;

        const telegramSuccess = await sendToTelegram(telegramMessage);
        if (!telegramSuccess) {
            alert('Failed to send checkout details to Telegram. Proceeding with checkout anyway.');
        }

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
                    localStorage.removeItem('cartItems');
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
            document.getElementById('qr-container').style.display = 'none';
            localStorage.removeItem('cartItems');
            updateCartCount();
            hideCart();
            document.getElementById('moreOptionsContainer').style.display = 'block';
        }
    });

    const {
        jsPDF
    } = window.jspdf;

    document.getElementById('savePdf').addEventListener('click', function() {
        const doc = new jsPDF();
        doc.setFont("helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.setFontSize(20);
        doc.text('Engly Store', 105, 15, {
            align: 'center'
        });
        doc.setFontSize(10);
        doc.setFont("helvetica", "normal");
        doc.text('Phum TropeagnChhuk, Sangkat Tekla, Khan SenSok, Phnom Penh City', 105, 23, {
            align: 'center'
        });
        doc.text('Phone: (+855) 97 45 67 89', 105, 28, {
            align: 'center'
        });
        doc.text(`Date: ${new Date().toLocaleString()}`, 10, 35);
        doc.text(`Cashier`, 180, 35, {
            align: 'right'
        });
        doc.setLineWidth(0.5);
        doc.line(10, 40, 200, 40);
        let y = 50;
        doc.setFontSize(12);
        doc.setFont("helvetica", "bold");
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
        doc.setLineWidth(0.2);
        doc.line(10, y, 200, y);
        y += 5;
        doc.setFont("helvetica", "normal");
        doc.setFontSize(10);
        let totalItems = 0;
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
            totalItems += parseInt(qty);
            y += 8;
        });
        doc.setLineWidth(0.2);
        doc.line(10, y, 200, y);
        y += 10;
        doc.setFontSize(12);
        doc.setFont("helvetica", "bold");
        const grandTotal = document.getElementById('grandTotal').textContent;
        doc.text(`Total Items: ${totalItems}`, 10, y);
        doc.text(`Subtotal: $${grandTotal}`, 180, y, {
            align: 'right'
        });
        y += 10;
        doc.setLineWidth(0.5);
        doc.line(10, y, 200, y);
        y += 10;
        doc.setFontSize(12);
        doc.setFont("helvetica", "bold");
        doc.text('THANK YOU FOR SHOPPING WITH US!', 105, y, {
            align: 'center'
        });
        y += 8;
        doc.setFontSize(8);
        doc.setFont("helvetica", "normal");
        doc.text('engly@gmail.com', 105, y, {
            align: 'center'
        });
        y += 9;
        const qrImage = new Image();
        qrImage.src = '../../views/assets/images/QR-code.png';
        qrImage.crossOrigin = 'Anonymous';
        qrImage.onload = function() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = qrImage.width;
            canvas.height = qrImage.height;
            ctx.drawImage(qrImage, 0, 0);
            const qrDataURL = canvas.toDataURL('image/png');
            doc.addImage(qrDataURL, 'PNG', 95, y, 20, 20);
            doc.save('pos-receipt.pdf');
        };
        qrImage.onerror = function() {
            console.error('Failed to load QR code image.');
            doc.text('[QR Code Failed to Load]', 105, y, {
                align: 'center'
            });
            doc.save('pos-receipt.pdf');
        };
    });

    document.getElementById('completeCart').addEventListener('click', function() {
        const qrContainer = document.getElementById('qr-container');
        const moreOptionsBtn = document.getElementById('moreOptionsBtn');
        const optionsDropdown = document.getElementById('optionsDropdown');
        const moreOptionsContainer = document.getElementById('moreOptionsContainer');
        qrContainer.style.display = 'block';
        moreOptionsContainer.style.display = 'none';
        qrContainer.innerHTML = `
            <img id="qr-code-img" src="../../views/assets/images/QR-code.png" alt="QR Code" style="width: 80px; height: 80px; margin-bottom: 15px;" />
            <input type="text" id="inputField" placeholder="Enter your details" style="margin-bottom: 15px;" />
            <button class="cart-btn cart-btn-info" id="savePdf2">Save PDF</button>
            <button class="cart-btn cart-btn-primary" id="completeCart2">Complete</button>
            <button class="cart-btn cart-btn-danger" id="clearCart2">Clear</button>
        `;
        document.getElementById('savePdf2').addEventListener('click', function() {
            document.getElementById('savePdf').click();
        });
        document.getElementById('completeCart2').addEventListener('click', function() {
            alert('Order completed!');
            qrContainer.style.display = 'none';
            moreOptionsContainer.style.display = 'block';
        });
        document.getElementById('clearCart2').addEventListener('click', function() {
            document.getElementById('clearCart').click();
        });
        optionsDropdown.classList.remove('visible');
        showCart();
    });

    const searchInput = document.querySelector('.search-container input');
    const productCards = document.querySelectorAll('.product-col');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim().toLowerCase();
        productCards.forEach(card => {
            const productName = card.querySelector('.card-title').textContent.trim().toLowerCase();
            if (productName.includes(searchTerm)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    });

    const moreOptionsBtn = document.getElementById('moreOptionsBtn');
    const optionsDropdown = document.getElementById('optionsDropdown');

    moreOptionsBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        optionsDropdown.classList.toggle('visible');
        moreOptionsBtn.classList.toggle('active');
    });

    document.addEventListener('click', function(e) {
        if (!moreOptionsBtn.contains(e.target) && !optionsDropdown.contains(e.target)) {
            optionsDropdown.classList.remove('visible');
            moreOptionsBtn.classList.remove('active');
        }
    });

    // New Kebab Menu Functionality
    document.querySelectorAll('.kebab-menu').forEach(menu => {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;
            document.querySelectorAll('.dropdown-menu').forEach(d => {
                if (d !== dropdown) d.classList.remove('visible');
            });
            dropdown.classList.toggle('visible');
        });
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.stopPropagation();
            const inventoryId = this.dataset.id;
            if (confirm('Are you sure you want to delete this product?')) {
                try {
                    const response = await fetch('/products/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            inventoryId
                        })
                    });
                    if (!response.ok) throw new Error(`Server error: ${response.status}`);
                    const data = await response.json();
                    if (data.success) {
                        this.closest('.product-col').remove();
                        alert('Product deleted successfully!');
                    } else {
                        alert(`Error: ${data.message}`);
                    }
                } catch (error) {
                    console.error('Delete failed:', error);
                    alert(`Failed to delete product: ${error.message}`);
                }
            }
            this.closest('.dropdown-menu').classList.remove('visible');
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.kebab-menu') && !e.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
                dropdown.classList.remove('visible');
            });
        }
    });
</script>