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
        padding: 8px;
    }

    #moreOptionsBtn {
        padding: 8px;
        width: 100%;
        max-width: 180px;
        color: #000;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        font-size: 0.9rem;
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
        transition: width 0.3s ease, opacity 0.3s ease;
        position: relative;
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
        padding: 5px 10px;
        border-radius: 5px;
        width: 50%;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-size: 0.9rem;
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
        outline: none;
        border: none;
    }

    .cart-footer {
        padding: 15px;
        background-color: #f8f9fa;
        text-align: center;
        border-top: 1px solid #ddd;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        position: relative;
        z-index: 900;
    }

    .cart-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.2s ease, transform 0.1s ease;
        width: 100%;
        max-width: 180px;
    }

    .cart-btn:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .cart-btn-success {
        padding: 10px 20px;
        font-size: 1rem;
        max-width: 200px;
        background-color: #28a745;
    }

    .cart-btn-success:hover:not(:disabled) {
        background-color: #218838;
        transform: scale(1.05);
    }

    .more-options {
        position: relative;
        width: 100%;
        max-width: 180px;
    }

    .cart-btn-secondary {
        background-color: #6c757d;
    }

    .cart-btn-secondary:hover:not(:disabled) {
        background-color: #5a6268;
    }

    .options-dropdown {
        display: none;
        position: absolute;
        bottom: calc(100% + 5px);
        left: 0;
        background-color: #fff;
        box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.15);
        border-radius: 6px;
        z-index: 1002;
        width: 100%;
        max-width: 180px;
        padding: 5px;
        border: 1px solid #eee;
    }

    .options-dropdown.visible {
        display: flex;
        flex-direction: column;
    }

    .options-dropdown button {
        width: 100%;
        border: none;
        margin: 2px 0;
        padding: 10px;
        font-size: 0.9rem;
        color: #fff;
        text-align: center;
        transition: all 0.2s ease;
        border-radius: 4px;
        font-weight: 500;
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

    #qr-container {
        margin-top: 15px;
        text-align: center;
        display: none;
    }

    .qr-section {
        display: none;
        text-align: center;
        padding: 10px;
    }

    .qr-section.active {
        display: block !important;
    }

    .qr-section img {
        cursor: pointer;
        width: 80px;
        height: 80px;
        margin-bottom: 15px;
    }

    .qr-buttons {
        margin-top: 10px;
    }

    .qr-buttons .cart-btn {
        margin: 5px auto;
    }

    #qr-container .cart-btn-confirm {
        background-color: #28a745;
    }

    #qr-container .cart-btn-confirm:hover:not(:disabled) {
        background-color: #218838;
    }

    #qr-container .cart-btn-alt {
        background-color: #17a2b8;
    }

    #qr-container .cart-btn-alt:hover:not(:disabled) {
        background-color: #138496;
    }

    #cartToggle {
        font-size: 20px;
    }

    #startWebcamScan {
        padding: 8px;
        margin-left: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        display: none;
    }

    #startWebcamScan:hover {
        background-color: #0056b3;
    }

    .barcode-scanner {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 15px;
        gap: 10px;
    }

    .barcode-scanner input {
        padding: 8px;
        width: 200px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .barcode-scanner input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        background-color: #f0f8ff;
    }

    .container-categories {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 20px;
        display: none;
    }

    .category {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 110px;
        padding: 10px;
        border-radius: 8px;
        background-color: #fff;
        transition: background-color 0.3s;
        transition: all 0.3s ease-in-out;
        cursor: pointer;
    }

    .category:hover {
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
        outline: 2px solid #ccc;
    }

    .category span {
        font-size: 24px;
    }

    .category p {
        margin: 5px 0 0;
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    .category img {
        display: block;
        margin: 0 auto;
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

        .cart-visible .product-col {
            width: 50%;
        }

        .cart-btn {
            max-width: 100%;
        }

        .cart-btn-success {
            padding: 8px 16px;
            font-size: 0.9rem;
            max-width: 100%;
        }

        .more-options {
            max-width: 100%;
        }

        .options-dropdown {
            max-width: 100%;
            left: 0;
            bottom: calc(100% + 5px);
        }

        .options-dropdown button {
            padding: 10px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .product-col {
            width: 100%;
        }

        .cart-visible .product-col {
            width: 100%;
        }
        
    }

    @media (min-width: 576px){
        .buy{
            width: 53%;
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
        padding: 6px 12px;
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
</style>

<?php require_once './views/layouts/nav.php' ?>

<main class="main-content">
    <div class="container-fluid">
        <div class="row" id="productRow">
            <div class="product-section">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Order Products</h3>
                    <!-- Barcode Input -->
                    <div class="barcode-scanner" style="margin-bottom: 15px;">
                        <input type="text" id="barcodeInput" placeholder="Scan or enter barcode" style="padding: 8px; width: 200px; border: 1px solid #ddd; border-radius: 4px;" autofocus />
                        <button id="startWebcamScan">Scan with Webcam</button>
                        <div id="webcamScanner" style="display: none;">
                            <video id="scannerVideo"></video>
                        </div>
                    </div>
                </div>

                <div class="container-categories">
                    <div class="category">
                        <span><img src="../../views/assets/icon/Kitchen.png" alt="Kitchen Tools" width="33" height="33"></span>
                        <p>Kitchen Tools</p>
                    </div>
                    <div class="category">
                        <span><img src="../../views/assets/icon/drinks.jpg" alt="Drinks & Water" width="33" height="33"></span>
                        <p>Drinks</p>
                    </div>
                    <div class="category">
                        <span><img src="../../views/assets/icon/Pot.png" alt="Cookware" width="33" height="33"></span>
                        <p>Cookware</p>
                    </div>
                    <div class="category">
                        <span><img src="../../views/assets/icon/Food.png" alt="Food Storage" width="33" height="33"></span>
                        <p>Food Storage</p>
                    </div>
                    <div class="category">
                        <span><img src="../../views/assets/icon/tableware.png" alt="Tableware" width="33" height="33"></span>
                        <p>Tableware</p>
                    </div>
                    <div class="category">
                        <span><img src="../../views/assets/icon/cutlery.png" alt="Cutlery" width="33" height="33"></span>
                        <p>Cutlery</p>
                    </div>
                    <div class="category">
                        <span><img src="../../views/assets/icon/cleaning.png" alt="Cleaning Supplies" width="33" height="33"></span>
                        <p>Cleaning</p>
                    </div>
                    <div class="category">
                        <span><img src="../../views/assets/icon/Supplier.png" alt="Small Kitchen Appliances" width="33" height="33"></span>
                        <p>Small Kitchen</p>
                    </div>
                </div>


                <div class="row" id="productGrid">
                    <?php foreach ($inventory as $item): ?>
                        <div class="product-col" data-barcode="<?= htmlspecialchars($item['barcode'] ?? '') ?>" data-inventory-id="<?= htmlspecialchars($item['inventory_id']) ?>">
                            <div class="product-card">
                                <i class="fa-solid fa-ellipsis-vertical kebab-menu"></i>
                                <div class="dropdown-menu">
                                    <p class="delete-btn" data-id="<?= htmlspecialchars($item['inventory_id']) ?>"><i class="fa-solid fa-trash"></i></p>
                                </div>
                                <div class="image-wrapper">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['inventory_product_name']) ?>" onerror="this.src='/views/assets/images/default-product.jpg'">
                                    <?php else: ?>
                                        <img src="/views/assets/images/default-product.jpg" alt="Default Product Image">
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between m-2">
                                        <h6 class="card-title text-left"><?= htmlspecialchars($item['inventory_product_name']) ?></h6>
                                        <p style="font-size: 13px;">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between m-2 mt-n2">
                                        <p class="price fw-bold" data-id="<?= htmlspecialchars($item['inventory_id']) ?>">
                                            $<?= htmlspecialchars($item['selling_price'] ?? $item['amount']) ?>
                                        </p>
                                        <p class="quantity" data-id="<?= htmlspecialchars($item['inventory_id']) ?>" style="display: none;">
                                            Qty: <?= htmlspecialchars($item['quantity']) ?>
                                        </p>
                                        <input type="hidden" name="inventory_id" value="<?= htmlspecialchars($item['inventory_id']) ?>" />
                                        <button class="buy" data-inventory-id="<?= htmlspecialchars($item['inventory_id']) ?>">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="pagination-controls">
                    <?php if ($currentPage > 1) : ?>
                        <a href="?page=<?= $currentPage - 1 ?>"><i class="fa-solid fa-less-than"></i></a>
                    <?php else : ?>
                        <button disabled><i class="fa-solid fa-less-than"></i></button>
                    <?php endif; ?>

                    <span>Page <?= $currentPage ?> of <?= $totalPages ?></span>

                    <?php if ($currentPage < $totalPages) : ?>
                        <a href="?page=<?= $currentPage + 1 ?>"><i class="fa-solid fa-greater-than"></i></a>
                    <?php else : ?>
                        <button disabled><i class="fa-solid fa-greater-than"></i></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="cart-section" id="cartSection">
            <div class="cart-card">
                <div class="cart-header bg-transparent">
                    <h4 class="text-dark mt-n2">POS Payout</h4>
                    <button class="close-cart text-dark" id="closeCart">✖</button>
                </div>
                <div class="cart-body mt-n4">
                    <table class="cart-table" id="cartTable">
                        <thead>
                            <tr>
                                <th style="border-bottom-left-radius: 10px;">Item</th>
                                <th>Qty</th>
                                <th>Price($)</th>
                                <th style="border-bottom-right-radius: 10px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cartBody"></tbody>
                    </table>
                    <div style="display: flex; justify-content: space-between; margin-top: 15px;">
                        <h6 style="font-weight: bold; flex-grow: 1;">Total:</h5>
                            <h6 style="font-weight: bold;">$<span id="grandTotal">20.00</span></h5>
                    </div>

                    <div id="qr-container" style="display: none;">
                        <div id="aclida-qr" class="qr-section active">
                            <img id="aclida-qr-img" src="../../views/assets/images/QR-AC.png" alt="ACLIDA QR Code" style="width: 80px; height: 80px; margin-bottom: 15px; cursor: pointer;" />
                            <p>ACLIDA PAY</p>
                        </div>
                        <div id="aba-qr" class="qr-section">
                            <img id="aba-qr-img" src="../../views/assets/images/QR-code.png" alt="ABA QR Code" style="width: 80px; height: 80px; margin-bottom: 15px; cursor: pointer;" />
                            <p>ABA PAY</p>
                        </div>
                        <div class="qr-buttons">
                            <button class="cart-btn cart-btn-confirm" id="qrConfirmBtn">Complete</button>
                            <button class="cart-btn cart-btn-alt" id="savePdf">Save PDF</button>
                        </div>
                    </div>
                </div>
                <div class="cart-footer">
                    <div class="more-options" id="moreOptionsContainer">
                        <button class="cart-btn cart-btn-secondary bg-transparent" id="moreOptionsBtn">More Options</button>
                        <div class="options-dropdown" id="optionsDropdown">
                            <button class="cart-btn cart-btn-info" id="savePdf">Save PDF</button>
                            <button class="cart-btn cart-btn-primary" id="completeCartBtn">Payout</button>
                            <button class="cart-btn cart-btn-danger" id="clearCart">Clear</button>
                        </div>
                    </div>
                    <button class="cart-btn cart-btn-success" id="submitCart">Complete order</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Toast Notification -->
    <div id="toast"></div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Main cart functionality variables
        const barcodeInput = document.getElementById('barcodeInput');
        const cartSection = document.getElementById('cartSection');
        const cartBody = document.getElementById('cartBody');
        const grandTotal = document.getElementById('grandTotal');
        const submitCartBtn = document.getElementById('submitCart');
        const moreOptionsBtn = document.getElementById('moreOptionsBtn');
        const optionsDropdown = document.getElementById('optionsDropdown');
        const savePdfBtn = document.getElementById('savePdf');
        const completeCartBtn = document.getElementById('completeCartBtn');
        const clearCartBtn = document.getElementById('clearCart');
        const qrContainer = document.getElementById('qr-container');
        const qrConfirmBtn = document.getElementById('qrConfirmBtn');
        const startWebcamScanBtn = document.getElementById('startWebcamScan');
        const cartToggle = document.createElement('span');
        cartToggle.id = 'cartToggle';
        cartToggle.className = 'cart-icon';
        cartToggle.innerHTML = '<i class="fa-solid fa-cart-shopping"></i><span class="cart-count" id="cartCount">0</span>';
        document.querySelector('.navbar').appendChild(cartToggle);
        const cartCount = document.getElementById('cartCount');

        // QR code switching variables
        const aclidaQrImg = document.getElementById('aclida-qr-img');
        const abaQrImg = document.getElementById('aba-qr-img');
        const aclidaSection = document.getElementById('aclida-qr');
        const abaSection = document.getElementById('aba-qr');

        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        let toastTimeout = null;
        let isProcessingScan = false;
        let lastScanTime = 0;

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

        // Initialize QR code section (ACLIDA by default)
        if (aclidaSection && abaSection) {
            aclidaSection.classList.add('active');
            abaSection.classList.remove('active');
            console.log('Initialized QR: ACLIDA active');
        } else {
            console.error('QR sections not found: aclidaSection or abaSection is null');
        }

        // Show toast notification
        function showToast(message, duration = 2000) {
            const toast = document.getElementById('toast');
            if (!toast) {
                console.error('Toast element not found');
                return;
            }
            console.log('Showing toast:', message);
            if (toastTimeout) clearTimeout(toastTimeout);
            toast.textContent = message;
            toast.style.display = 'block';
            toastTimeout = setTimeout(() => {
                toast.style.display = 'none';
                toastTimeout = null;
            }, duration);
        }

        // Play scan sound
        function playScanSound() {
            const audio = new Audio('/views/assets/sounds/scan-beep.mp3');
            audio.play().catch(err => console.error('Audio playback failed:', err));
        }

        // Clear UI state and localStorage
        function clearUIState() {
            document.querySelectorAll('.product-col').forEach(col => col.classList.remove('highlight'));
            if (barcodeInput) barcodeInput.value = '';
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page') || '1';
            window.history.replaceState({}, document.title, `?page=${page}`);
            toggleCart(false);
            cartItems = [];
            localStorage.removeItem('cartItems');
            qrContainer.style.display = 'none';
            updateCartUI();
        }

        // Reset barcode input
        function resetBarcodeInput() {
            if (barcodeInput) {
                barcodeInput.value = '';
                console.log('Barcode input reset');
            }
        }

        // Add to cart (debounced)
        const debouncedAddToCart = debounce(function(item, isScanned = false) {
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
                cartCount.textContent = cartItems.length; // Update cart count immediately
                cartCount.classList.toggle('visible', cartItems.length > 0);
            }
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            updateCartUI();
            toggleCart(true);
            playScanSound();
            showToast(`${item.inventory_product_name} added to cart!`);
        }, 300);

        // Update cart UI
        function updateCartUI() {
            if (!cartBody || !grandTotal || !cartCount) {
                console.error('Cart elements missing: cartBody, grandTotal, or cartCount');
                return;
            }
            cartBody.innerHTML = '';
            let total = 0;
            cartItems.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${item.name}</td>
                <td><input type="number" class="cart-qty" value="${item.quantity}" min="1" data-index="${index}"></td>
                <td><input type="number" class="cart-price" value="${item.price.toFixed(2)}" step="0.01" data-index="${index}"></td>
                <td><span class="remove-item" data-index="${index}"><i class="fa-solid fa-trash"></i></span></td>
            `;
                cartBody.appendChild(row);
                total += item.quantity * item.price;
            });
            grandTotal.textContent = total.toFixed(2);
            // Update cart count (unique products)
            cartCount.textContent = cartItems.length;
            cartCount.classList.toggle('visible', cartItems.length > 0);
            console.log('Cart count updated:', cartItems.length, 'unique products');
            savePdfBtn.disabled = cartItems.length === 0;
            submitCartBtn.disabled = cartItems.length === 0;
            completeCartBtn.disabled = cartItems.length === 0;
            clearCartBtn.disabled = cartItems.length === 0;
            if (qrConfirmBtn) qrConfirmBtn.disabled = cartItems.length === 0;

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
            if (!cartSection || !document.querySelector('.main-content')) return;
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
            console.log('Debounced scan triggered for:', barcode);
            if (isProcessingScan) {
                console.log('Scan ignored: Processing in progress');
                return;
            }
            const now = Date.now();
            if (now - lastScanTime < 1000) {
                console.log('Scan ignored: Too soon after previous scan');
                return;
            }
            isProcessingScan = true;
            lastScanTime = now;
            if (barcodeInput) barcodeInput.disabled = true;

            barcode = barcode.trim().replace(/[\r\n\t]/g, '');
            console.log('Cleaned barcode:', barcode);

            document.querySelectorAll('.product-col').forEach(col => col.classList.remove('highlight'));

            const productCol = document.querySelector(`.product-col[data-barcode="${barcode}"]`);
            console.log('Product in DOM:', productCol);
            if (productCol) {
                const item = {
                    inventory_id: productCol.dataset.inventoryId,
                    inventory_product_name: productCol.querySelector('.card-title').textContent,
                    selling_price: parseFloat(productCol.querySelector('.price').textContent.replace('$', '')),
                    image: productCol.querySelector('img').src,
                    quantity: 1
                };
                debouncedAddToCart(item, true);
                productCol.classList.add('highlight');
                resetBarcodeInput();
                isProcessingScan = false;
                if (barcodeInput) barcodeInput.disabled = false;
                return;
            }

            console.log('Fetching product from backend for barcode:', barcode);
            fetch('/products/getProductPageByBarcode', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        barcode: barcode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Backend response:', data);
                    if (data.success && data.item) {
                        const item = {
                            inventory_id: data.item.inventory_id,
                            inventory_product_name: data.item.inventory_product_name,
                            selling_price: parseFloat(data.item.selling_price || data.item.amount),
                            image: data.item.image,
                            quantity: 1
                        };
                        debouncedAddToCart(item, true);
                        resetBarcodeInput();
                        const productCol = document.querySelector(`.product-col[data-barcode="${barcode}"]`);
                        if (productCol) productCol.classList.add('highlight');
                    } else {
                        resetBarcodeInput();
                        showToast(data.message || 'Barcode not found.', 2000);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    resetBarcodeInput();
                    showToast('Error scanning barcode.', 2000);
                })
                .finally(() => {
                    console.log('Resetting scan state');
                    isProcessingScan = false;
                    if (barcodeInput) barcodeInput.disabled = false;
                });
        }, 1000);

        // Barcode input handling
        if (barcodeInput) {
            barcodeInput.addEventListener('keypress', function(e) {
                console.log('Barcode input keypress:', e.key, 'Value:', e.target.value);
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const barcode = e.target.value.trim();
                    if (barcode && !isProcessingScan) {
                        console.log('Processing barcode via Enter key:', barcode);
                        debouncedHandleBarcodeScan(barcode);
                    } else {
                        console.log('Scan ignored: Empty barcode or processing in progress');
                    }
                }
            });
        } else {
            console.error('Barcode input element not found');
        }

        // Handle URL barcode parameter
        const urlParams = new URLSearchParams(window.location.search);
        const scannedBarcode = urlParams.get('barcode');
        if (scannedBarcode) {
            console.log('Processing URL barcode:', scannedBarcode);
            debouncedHandleBarcodeScan(scannedBarcode);
            window.history.replaceState({}, document.title, `?page=${urlParams.get('page') || '1'}`);
        }

        // Add to cart button
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

        // Cart toggle button
        cartToggle.addEventListener('click', function() {
            toggleCart(!cartSection.classList.contains('visible'));
        });

        // Submit cart
        submitCartBtn.addEventListener('click', function() {
            if (cartItems.length === 0) return;
            fetch('/products/submitCart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
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
                        showToast('Order completed successfully!');
                        clearUIState();
                    } else {
                        showToast(data.message || 'Error submitting order.', 2000);
                    }
                })
                .catch(error => {
                    console.error('Submit cart error:', error);
                    showToast('Error submitting order.', 2000);
                });
        });

        // Toggle dropdown
        moreOptionsBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isVisible = optionsDropdown.classList.contains('visible');
            optionsDropdown.classList.toggle('visible', !isVisible);
            moreOptionsBtn.classList.toggle('active', !isVisible);
            console.log('Toggling dropdown, current state:', !isVisible);
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
        function saveCartAsPDF() {
            if (cartItems.length === 0) return;
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
            doc.text('POS Receipt', 105, 20, {
                align: 'center'
            });
            doc.setFontSize(12);
            doc.setFont('helvetica', 'normal');
            doc.text('Store Name', 105, 30, {
                align: 'center'
            });
            doc.text('123 Main Street, City, Country', 105, 35, {
                align: 'center'
            });
            doc.text(new Date().toLocaleString(), 105, 40, {
                align: 'center'
            });

            doc.setLineWidth(0.5);
            doc.line(20, 45, 190, 45);

            doc.setFontSize(10);
            doc.setFont('helvetica', 'bold');
            doc.text('Item', 20, 50);
            doc.text('Qty', 120, 50);
            doc.text('Price', 150, 50);
            doc.text('Total', 180, 50);

            doc.setLineWidth(0.2);
            doc.line(20, 52, 190, 52);

            let y = 60;
            doc.setFont('helvetica', 'normal');
            cartItems.forEach(item => {
                const itemName = item.name.length > 30 ? item.name.substring(0, 27) + '...' : item.name;
                doc.text(itemName, 20, y);
                doc.text(item.quantity.toString(), 120, y);
                doc.text('$' + item.price.toFixed(2), 150, y);
                doc.text('$' + (item.quantity * item.price).toFixed(2), 180, y);
                y += 8;
            });

            doc.setLineWidth(0.5);
            doc.line(20, y, 190, y);
            y += 5;

            doc.setFontSize(12);
            doc.setFont('helvetica', 'bold');
            doc.text('Total: $' + grandTotal.textContent, 180, y, {
                align: 'right'
            });

            y += 15;
            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            doc.text('Thank you for your purchase!', 105, y, {
                align: 'center'
            });
            doc.text('Contact: store@example.com', 105, y + 5, {
                align: 'center'
            });

            doc.save('pos-receipt.pdf');
            optionsDropdown.classList.remove('visible');
            moreOptionsBtn.classList.remove('active');
        }

        savePdfBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            saveCartAsPDF();
        });

        // Payout (show QR code and buttons)
        if (completeCartBtn) {
            completeCartBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (cartItems.length === 0) return;
                qrContainer.style.display = 'block';
                optionsDropdown.classList.remove('visible');
                moreOptionsBtn.classList.remove('active');
                console.log('Showing QR container');
            });
        }

        // QR Confirm (submit cart)
        if (qrConfirmBtn) {
            qrConfirmBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                submitCartBtn.click();
                qrContainer.style.display = 'none';
            });
        }

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
            qrContainer.style.display = 'none';
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
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            inventoryId: inventoryId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('.product-col').remove();
                            showToast('Item deleted successfully.');
                        } else {
                            showToast(data.message || 'Error deleting item.', 2000);
                        }
                    })
                    .catch(error => {
                        console.error('Delete item error:', error);
                        showToast('Error deleting item.', 2000);
                    });
            });
        });

        // Webcam scanning
        if (startWebcamScanBtn) {
            startWebcamScanBtn.addEventListener('click', function() {
                console.log('Starting webcam scan');
                const webcamScanner = document.getElementById('webcamScanner');
                if (!webcamScanner) {
                    console.error('Webcam scanner element not found');
                    showToast('Scanner UI not found.', 2000);
                    return;
                }
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
                        readers: ["ean_reader", "code_128_reader", "upc_reader", "code_39_reader"]
                    }
                }, function(err) {
                    if (err) {
                        console.error('Quagga init error:', err);
                        showToast('Failed to access webcam.', 2000);
                        webcamScanner.style.display = 'none';
                        return;
                    }
                    console.log('Quagga initialized successfully');
                    Quagga.start();
                });

                Quagga.onDetected(function(result) {
                    const barcode = result.codeResult.code;
                    console.log('Detected barcode:', barcode);
                    if (barcode && !isProcessingScan) {
                        debouncedHandleBarcodeScan(barcode);
                        Quagga.stop();
                        webcamScanner.style.display = 'none';
                    }
                });
            });
        }

        // QR code switching logic
        if (aclidaQrImg && abaQrImg && aclidaSection && abaSection) {
            aclidaQrImg.addEventListener('click', () => {
                console.log('Switching to ABA QR');
                aclidaSection.classList.remove('active');
                abaSection.classList.add('active');
            });

            abaQrImg.addEventListener('click', () => {
                console.log('Switching to ACLIDA QR');
                abaSection.classList.remove('active');
                aclidaSection.classList.add('active');
            });
        } else {
            console.error('QR elements not found: Check aclidaQrImg, abaQrImg, aclidaSection, or abaSection');
        }
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>