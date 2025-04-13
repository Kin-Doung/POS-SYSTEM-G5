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

    /* Kebab Menu Styles */
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
    }

    .cart-btn-success {
        padding: 14px 28px;
        font-size: 1.1rem;
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

    h3 {
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
                                        Selling Price: $<?= htmlspecialchars($item['selling_price'] ?? $item['amount']) ?>
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
                <style>
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
                </style>
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
                        <img id="qr-code-img" src="../../views/assets/images/QR-code.png" alt="QR Code" style="width: 80px; height: 80px; margin-bottom: 15px;" />
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
