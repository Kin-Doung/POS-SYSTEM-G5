<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>
<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<style>
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

    .product-col.highlight {
        border: 2px solid #007bff;
        background-color: #e7f1ff;
    }

    .product-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        height: 100%;
        transition: transform 0.2s ease;
        position: relative;
        z-index: 1;
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
    }

    .dropdown-menu.visible {
        display: block;
    }

    .dropdown-menu p {
        width: 100%;
        padding: 8px 15px;
        border: none;
        background: none;
        text-align: left;
        cursor: pointer;
        color: #dc3545;
        font-size: 14px;
    }

    .dropdown-menu p:hover {
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
        position: relative;
        z-index: 900;
    }

    .cart-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.2s ease, transform 0.1s ease;
        width: 100%;
        max-width: 200px;
        pointer-events: auto;
    }

    .cart-btn:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .cart-btn-success {
        padding: 14px 28px;
        font-size: 1.1rem;
        max-width: 220px;
        background-color: #28a745;
    }

    .cart-btn-success:hover:not(:disabled) {
        background-color: #218838;
    }

    .more-options {
        position: relative;
        width: 100%;
        max-width: 200px;
        overflow: visible;
    }

    .cart-btn-secondary {
        background-color: #6c757d;
    }

    .cart-btn-secondary:hover {
        background-color: #5a6268;
    }

    .options-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 5px);
        left: 50%;
        transform: translateX(-50%);
        background-color: #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        border-radius: 6px;
        z-index: 1001;
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
        pointer-events: auto;
        cursor: pointer;
    }

    .options-dropdown button:hover:not(:disabled) {
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

    .cart-btn-info:hover:not(:disabled) {
        background-color: #138496;
    }

    .cart-btn-danger {
        background-color: #dc3545;
    }

    .cart-btn-danger:hover:not(:disabled) {
        background-color: #c82333;
    }

    .cart-btn-primary {
        background-color: #007bff;
    }

    .cart-btn-primary:hover:not(:disabled) {
        background-color: #0056b3;
    }

    .cart-btn:hover:not(:disabled) {
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
            top: calc(100% + 5px);
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

    h3 {
        font-family: "Poppins", sans-serif;
        font-weight: bold;
        margin-bottom: 10px;
        color: #000;
    }

    .pagination-controls {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        font-family: Arial, sans-serif;
    }

    .pagination-controls a,
    .pagination-controls button {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .pagination-controls a {
        background-color: #007bff;
        color: white;
        text-decoration: none;
    }

    .pagination-controls a:hover {
        background-color: #0056b3;
    }

    .pagination-controls button {
        background-color: #ccc;
        color: #fff;
        cursor: not-allowed;
    }

    .pagination-controls span {
        font-weight: bold;
    }

    .barcode-scanner input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        background-color: #f0f8ff;
    }

    #toast {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #28a745;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        z-index: 1000;
    }

    #webcamScanner {
        margin-top: 10px;
    }

    #scannerVideo {
        width: 100%;
        max-width: 300px;
    }

    #startWebcamScan {
        padding: 8px;
        margin-left: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    #startWebcamScan:hover {
        background-color: #0056b3;
    }
</style>

<?php require_once './views/layouts/nav.php' ?>

<main class="main-content">
    <div class="container-fluid">
        <div class="row" id="productRow">
            <div class="product-section">
                <h3 data-translate-key="Order_Products">Order Products</h3>
                <!-- Barcode Input -->
                <div class="barcode-scanner" style="margin-bottom: 15px;">
                    <input type="text" id="barcodeInput" placeholder="Scan or enter barcode" data-translate-key="Scan_Or_Enter_Barcode" style="padding: 8px; width: 200px; border: 1px solid #ddd; border-radius: 4px;" autofocus />
                    <button id="startWebcamScan" data-translate-key="Scan_With_Webcam" title="Scan with Webcam" aria-label="Scan with Webcam">Scan with Webcam</button>
                    <div id="webcamScanner" style="display: none;">
                        <video id="scannerVideo"></video>
                    </div>
                </div>
                <div class="row" id="productGrid">
                    <?php foreach ($inventory as $item): ?>
                        <div class="product-col" data-barcode="<?= htmlspecialchars($item['barcode'] ?? '') ?>" data-inventory-id="<?= htmlspecialchars($item['inventory_id']) ?>">
                            <div class="product-card">
                                <i class="fa-solid fa-ellipsis-vertical kebab-menu"></i>
                                <div class="dropdown-menu">
                                    <p class="delete-btn" data-id="<?= htmlspecialchars($item['inventory_id']) ?>" data-translate-key="Delete" title="Delete" aria-label="Delete"><i class="fa-solid fa-trash"></i></p>
                                </div>
                                <div class="image-wrapper">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['inventory_product_name']) ?>" onerror="this.src='/views/assets/images/default-product.jpg'">
                                    <?php else: ?>
                                        <img src="/views/assets/images/default-product.jpg" alt="Default Product Image">
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($item['inventory_product_name']) ?></h6>
                                    <p class="price" data-id="<?= htmlspecialchars($item['inventory_id']) ?>">
                                        $<?= htmlspecialchars($item['selling_price'] ?? $item['amount']) ?>
                                    </p>
                                    <p class="quantity" data-id="<?= htmlspecialchars($item['inventory_id']) ?>" style="display: none;">
                                        Qty: <?= htmlspecialchars($item['quantity']) ?>
                                    </p>
                                    <input type="hidden" name="inventory_id" value="<?= htmlspecialchars($item['inventory_id']) ?>" />
                                    <button class="buy" data-inventory-id="<?= htmlspecialchars($item['inventory_id']) ?>" data-translate-key="Add_To_Cart" title="Add to Cart" aria-label="Add to Cart">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="pagination-controls">
                    <?php if ($currentPage > 1) : ?>
                        <a href="?page=<?= $currentPage - 1 ?>" title="Previous Page" aria-label="Previous Page"><i class="fa-solid fa-less-than"></i></a>
                    <?php else : ?>
                        <button disabled title="Previous Page" aria-label="Previous Page"><i class="fa-solid fa-less-than"></i></button>
                    <?php endif; ?>

                    <span>Page <?= $currentPage ?> of <?= $totalPages ?></span>

                    <?php if ($currentPage < $totalPages) : ?>
                        <a href="?page=<?= $currentPage + 1 ?>" title="Next Page" aria-label="Next Page"><i class="fa-solid fa-greater-than"></i></a>
                    <?php else : ?>
                        <button disabled title="Next Page" aria-label="Next Page"><i class="fa-solid fa-greater-than"></i></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="cart-section" id="cartSection">
            <div class="cart-card">
                <div class="cart-header">
                    <h4 data-translate-key="POS_Payout">POS Payout</h4>
                    <button class="close-cart" id="closeCart" title="Close Cart" aria-label="Close Cart">✖</button>
                </div>
                <div class="cart-body">
                    <table class="cart-table" id="cartTable">
                        <thead>
                            <tr>
                                <th data-translate-key="Item">Item</th>
                                <th data-translate-key="Qty">Qty</th>
                                <th data-translate-key="Price_Dollar">Price ($)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cartBody"></tbody>
                    </table>
                    <div style="text-align: right; margin-top: 15px;">
                        <h5 style="font-weight: bold;" data-translate-key="Total">Total: $<span id="grandTotal">0.00</span></h5>
                    </div>
                    <div id="qr-container" style="display: none;">
                        <img id="qr-code-img" src="../../views/assets/images/QR-code.png" alt="QR Code" style="width: 80px; height: 80px; margin-bottom: 15px;" />
                        <input type="text" id="inputField" placeholder="Enter your details" data-translate-key="Enter_Your_Details" />
                    </div>
                </div>
                <div class="cart-footer">
                    <div class="more-options" id="moreOptionsContainer">
                        <button class="cart-btn cart-btn-secondary" id="moreOptionsBtn" data-translate-key="More_Options" title="More Options" aria-label="More Options">More Options</button>
                        <div class="options-dropdown" id="optionsDropdown">
                            <button class="cart-btn cart-btn-info" id="savePdf" data-translate-key="Save_PDF" title="Save PDF" aria-label="Save PDF">Save PDF</button>
                            <button class="cart-btn cart-btn-primary" id="completeCart" data-translate-key="Payout" title="Payout" aria-label="Payout">Payout</button>
                            <button class="cart-btn cart-btn-danger" id="clearCart" data-translate-key="Clear" title="Clear" aria-label="Clear">Clear</button>
                        </div>
                    </div>
                    <button class="cart-btn cart-btn-success" id="submitCart" data-translate-key="Complete_Order" title="Complete Order" aria-label="Complete Order">Complete order</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Toast Notification -->
    <div id="toast"></div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    // Function to get translated text
    function getTranslation(key) {
        const lang = localStorage.getItem('selectedLanguage') || 'en';
        return translations[lang][key] || key;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const barcodeInput = document.getElementById('barcodeInput');
        const cartSection = document.getElementById('cartSection');
        const cartBody = document.getElementById('cartBody');
        const grandTotal = document.getElementById('grandTotal');
        const submitCartBtn = document.getElementById('submitCart');
        const moreOptionsBtn = document.getElementById('moreOptionsBtn');
        const optionsDropdown = document.getElementById('optionsDropdown');
        const savePdfBtn = document.getElementById('savePdf');
        const completeCartBtn = document.getElementById('completeCart');
        const clearCartBtn = document.getElementById('clearCart');
        const productGrid = document.getElementById('productGrid');
        const startWebcamScanBtn = document.getElementById('startWebcamScan');
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        let toastTimeout = null;
        let isProcessingScan = false;
        let lastScanTime = 0;

        // Apply translations on page load
        const savedLang = localStorage.getItem('selectedLanguage') || 'en';
        applyTranslations(savedLang);

        // Update tooltips and aria-labels dynamically
        document.querySelectorAll('[title][data-translate-key], [aria-label][data-translate-key]').forEach(element => {
            const key = element.getAttribute('data-translate-key');
            element.setAttribute('title', getTranslation(key));
            element.setAttribute('aria-label', getTranslation(key));
        });

        // Debounce function to limit rapid calls
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // Initialize cart UI from localStorage
        updateCartUI();

        // Show toast notification
        function showToast(messageKey, duration = 2000) {
            const toast = document.getElementById('toast');
            if (toastTimeout) {
                clearTimeout(toastTimeout);
            }
            toast.textContent = getTranslation(messageKey);
            toast.style.display = 'block';
            toastTimeout = setTimeout(() => {
                toast.style.display = 'none';
                toastTimeout = null;
            }, duration);
        }

        // Play scan sound
        function playScanSound() {
            const audio = new Audio('/views/assets/sounds/scan-beep.mp3');
            audio.play().catch(() => console.log('Audio playback failed'));
        }

        // Clear UI state and localStorage
        function clearUIState() {
            document.querySelectorAll('.product-col').forEach(col => {
                col.classList.remove('highlight');
            });
            barcodeInput.value = '';
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page') || '1';
            window.history.replaceState({}, document.title, `?page=${page}`);
            toggleCart(false);
            cartItems = [];
            localStorage.removeItem('cartItems');
            updateCartUI();
        }

        // Reset barcode input
        function resetBarcodeInput() {
            barcodeInput.value = '';
        }

        // Add to cart (debounced to prevent rapid clicks)
        const debouncedAddToCart = debounce(function(item) {
            console.log('Adding to cart:', item.inventory_product_name, 'ID:', item.inventory_id);
            const existingItem = cartItems.find(cartItem => cartItem.inventory_id === item.inventory_id);
            if (existingItem) {
                existingItem.quantity += 1;
                console.log('Incremented quantity for', item.inventory_product_name, 'to', existingItem.quantity);
            } else {
                cartItems.push({
                    inventory_id: item.inventory_id,
                    name: item.inventory_product_name,
                    price: parseFloat(item.selling_price || item.amount),
                    quantity: 1,
                    image: item.image
                });
                console.log('Added new item:', item.inventory_product_name);
            }
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            updateCartUI();
            toggleCart(true);
            playScanSound();
        }, 300);

        // Update cart UI
        function updateCartUI() {
            cartBody.innerHTML = '';
            let total = 0;
            cartItems.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td><input type="number" class="cart-qty" value="${item.quantity}" min="1" data-index="${index}"></td>
                    <td><input type="number" class="cart-price" value="${item.price.toFixed(2)}" step="0.01" data-index="${index}"></td>
                    <td><span class="remove-item" data-index="${index}" title="${getTranslation('Delete')}" aria-label="${getTranslation('Delete')}"><i class="fa-solid fa-trash"></i></span></td>
                `;
                cartBody.appendChild(row);
                total += item.quantity * item.price;
            });
            grandTotal.textContent = total.toFixed(2);
            savePdfBtn.disabled = cartItems.length === 0;
            submitCartBtn.disabled = cartItems.length === 0;
            completeCartBtn.disabled = cartItems.length === 0;

            document.querySelectorAll('.cart-qty').forEach(input => {
                input.addEventListener('change', function() {
                    const index = this.dataset.index;
                    const newQty = parseInt(this.value);
                    if (newQty > 0) {
                        cartItems[index].quantity = newQty;
                        localStorage.setItem('cartItems', JSON.stringify(cartItems));
                        updateCartUI();
                    }
                });
            });

            document.querySelectorAll('.cart-price').forEach(input => {
                input.addEventListener('change', function() {
                    const index = this.dataset.index;
                    const newPrice = parseFloat(this.value);
                    if (newPrice >= 0) {
                        cartItems[index].price = newPrice;
                        localStorage.setItem('cartItems', JSON.stringify(cartItems));
                        updateCartUI();
                    }
                });
            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.dataset.index;
                    cartItems.splice(index, 1);
                    localStorage.setItem('cartItems', JSON.stringify(cartItems));
                    updateCartUI();
                });
            });
        }

        // Toggle cart visibility
        function toggleCart(show) {
            if (show) {
                cartSection.classList.add('visible');
                document.querySelector('.main-content').classList.add('cart-visible');
            } else {
                cartSection.classList.remove('visible');
                document.querySelector('.main-content').classList.remove('cart-visible');
            }
        }

        // Handle barcode scan (debounced)
        const debouncedHandleBarcodeScan = debounce(function(barcode) {
            if (isProcessingScan) {
                console.log('Scan ignored: Processing in progress');
                return;
            }
            const now = Date.now();
            if (now - lastScanTime < 500) {
                console.log('Scan ignored: Too soon after previous scan');
                return;
            }
            isProcessingScan = true;
            lastScanTime = now;
            barcodeInput.disabled = true;

            barcode = barcode.trim().replace(/[\r\n\t]/g, '');
            console.log('Scanned barcode:', barcode);

            document.querySelectorAll('.product-col').forEach(col => {
                col.classList.remove('highlight');
            });

            // Check current page first
            const productCol = document.querySelector(`.product-col[data-barcode="${barcode}"]`);
            if (productCol) {
                productCol.classList.add('highlight');
                const buyButton = productCol.querySelector('.buy');
                if (buyButton) {
                    buyButton.click();
                    resetBarcodeInput();
                    isProcessingScan = false;
                    barcodeInput.disabled = false;
                    return;
                }
            }

            // Fetch product from backend (any page)
            fetch('/products/getProductPageByBarcode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>'
                },
                body: JSON.stringify({
                    barcode: barcode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.item) {
                    const currentPage = parseInt(new URLSearchParams(window.location.search).get('page') || '1');
                    if (data.page && data.page !== currentPage) {
                        // Redirect to the correct page with barcode parameter
                        window.location.href = `?page=${data.page}&barcode=${encodeURIComponent(barcode)}`;
                    } else {
                        // Product is on current page or page info not provided, add to cart
                        debouncedAddToCart(data.item);
                        resetBarcodeInput();
                        const productCol = document.querySelector(`.product-col[data-barcode="${barcode}"]`);
                        if (productCol) {
                            productCol.classList.add('highlight');
                        }
                    }
                } else {
                    resetBarcodeInput();
                    showToast(data.message ? data.message : 'Barcode_Not_Found', 2000);
                }
            })
            .catch(error => {
                console.error('Error fetching product:', error);
                resetBarcodeInput();
                showToast('Error_Scanning_Barcode', 2000);
            })
            .finally(() => {
                isProcessingScan = false;
                barcodeInput.disabled = false;
            });
        }, 500);

        // Barcode input handling
        barcodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const barcode = e.target.value.trim();
                if (barcode && !isProcessingScan) {
                    console.log('Processing barcode via Enter key');
                    debouncedHandleBarcodeScan(barcode);
                }
            }
        });

        // Handle URL barcode parameter
        const urlParams = new URLSearchParams(window.location.search);
        const scannedBarcode = urlParams.get('barcode');
        if (scannedBarcode) {
            const productCol = document.querySelector(`.product-col[data-barcode="${scannedBarcode}"]`);
            if (productCol) {
                productCol.classList.add('highlight');
                const buyButton = productCol.querySelector('.buy');
                if (buyButton) {
                    buyButton.click();
                    resetBarcodeInput();
                    window.history.replaceState({}, document.title, `?page=${urlParams.get('page') || '1'}`);
                }
            }
        }

        // Add to cart button (with debouncing)
        document.querySelectorAll('.buy').forEach(button => {
            button.addEventListener('click', function() {
                const inventoryId = this.dataset.inventoryId;
                const productCol = this.closest('.product-col');
                const item = {
                    inventory_id: inventoryId,
                    inventory_product_name: productCol.querySelector('.card-title').textContent,
                    selling_price: parseFloat(productCol.querySelector('.price').textContent.replace('$', '')),
                    image: productCol.querySelector('img').src,
                    quantity: 1
                };
                console.log('Button clicked for', item.inventory_product_name);
                debouncedAddToCart(item);
            });
        });

        // Submit cart
        submitCartBtn.addEventListener('click', function() {
            if (cartItems.length === 0) return;
            fetch('/products/submitCart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>'
                },
                body: JSON.stringify({
                    cartItems: cartItems.map(item => ({
                        inventoryId: item.inventory_id,
                        quantity: item.quantity,
                        price: item.price
                    }))
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Order_Completed_Successfully');
                    clearUIState();
                } else {
                    showToast(data.message || 'Error_Submitting_Order', 2000);
                }
            })
            .catch(error => {
                showToast('Error_Submitting_Order', 2000);
            });
        });

        // Toggle dropdown
        moreOptionsBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isVisible = optionsDropdown.classList.contains('visible');
            optionsDropdown.classList.toggle('visible', !isVisible);
            moreOptionsBtn.classList.toggle('active', !isVisible);
        });

        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            if (!moreOptionsBtn.contains(e.target) && !optionsDropdown.contains(e.target)) {
                optionsDropdown.classList.remove('visible');
                moreOptionsBtn.classList.remove('active');
            }
        });

        // Prevent dropdown close on inside click
        optionsDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Save PDF
        savePdfBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (cartItems.length === 0) return;
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text(getTranslation('POS_Payout'), 10, 10);
            let y = 20;
            cartItems.forEach(item => {
                doc.text(`${item.name}: ${item.quantity} x $${item.price.toFixed(2)}`, 10, y);
                y += 10;
            });
            doc.text(`${getTranslation('Total')}: $${grandTotal.textContent}`, 10, y);
            doc.save('cart-receipt.pdf');
            optionsDropdown.classList.remove('visible');
            moreOptionsBtn.classList.remove('active');
        });

        // Payout (triggers submitCart)
        completeCartBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            submitCartBtn.click();
            optionsDropdown.classList.remove('visible');
            moreOptionsBtn.classList.remove('active');
        });

        // Clear cart
        clearCartBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            cartItems = [];
            localStorage.removeItem('cartItems');
            updateCartUI();
            toggleCart(false);
            optionsDropdown.classList.remove('visible');
            moreOptionsBtn.classList.remove('active');
        });

        // Close cart
        document.getElementById('closeCart').addEventListener('click', () => {
            toggleCart(false);
        });

        // Kebab menu
        document.querySelectorAll('.kebab-menu').forEach(menu => {
            menu.addEventListener('click', function() {
                const dropdown = this.nextElementSibling;
                dropdown.classList.toggle('visible');
            });
        });

        // Delete item
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const inventoryId = this.dataset.id;
                fetch('/products/deleteInventory', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>'
                    },
                    body: JSON.stringify({
                        inventoryId: inventoryId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.product-col').remove();
                        showToast('Item_Deleted_Successfully');
                    } else {
                        showToast(data.message || 'Error_Deleting_Item', 2000);
                    }
                })
                .catch(error => {
                    showToast('Error_Deleting_Item', 2000);
                });
            });
        });

        // Webcam scanning
        startWebcamScanBtn.addEventListener('click', function() {
            const webcamScanner = document.getElementById('webcamScanner');
            webcamScanner.style.display = 'block';
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.getElementById('scannerVideo'),
                    constraints: {
                        facingMode: "environment"
                    }
                },
                decoder: {
                    readers: ["ean_reader", "code_128_reader", "upc_reader"]
                }
            }, function(err) {
                if (err) {
                    console.error(err);
                    showToast('Failed_To_Access_Webcam', 2000);
                    webcamScanner.style.display = 'none';
                    return;
                }
                Quagga.start();
            });

            Quagga.onDetected(function(result) {
                const barcode = result.codeResult.code;
                if (barcode && !isProcessingScan) {
                    console.log('Webcam scan:', barcode);
                    debouncedHandleBarcodeScan(barcode);
                    Quagga.stop();
                    webcamScanner.style.display = 'none';
                }
            });
        });
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>