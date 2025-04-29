<?php
// stock_tracking.php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" style="margin-left: 250px;">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php require_once './views/layouts/nav.php' ?>
                <div class="container table-inventory" style="background-color: #fff; height:auto">
                    <div class="orders">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 style="font-weight: bold;" class="purchase-head" data-translate-key="Stock tracking">Stock Tracking</h2>
                        </div>
                        <div class="input-group">
                            <select id="categorySelect" class="ms-2 selected" onchange="filterTable()" style="border-radius: 0;">
                                <option value="" data-translate-key="Select Category">Select Category</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category['id']) ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option disabled data-translate-key="No Categories Found">No Categories Found</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th data-translate-key="Image">Image</th>
                                    <th data-translate-key="Product Name">Product Name</th>
                                    <th data-translate-key="Quantity">Quantity</th>
                                    <th data-translate-key="Status">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lowStockItems = [];
                                foreach ($tracking as $index => $item):
                                    $quantity = (int)$item['quantity'];
                                    $status = $quantity >= 50 ? 'High' : ($quantity >= 10 ? 'Medium' : 'Low');
                                    if ($status === 'Low') {
                                        $lowStockItems[] = [
                                            'id' => $item['id'] ?? '',
                                            'product_name' => $item['product_name'] ?? 'Unknown',
                                            'quantity' => $quantity,
                                            'image' => $item['image'] ?? '../../views/assets/images/default.png',
                                            'category_id' => $item['category_id'] ?? '',
                                            'category_name' => $item['category_name'] ?? 'N/A'
                                        ];
                                    }
                                ?>
                                    <tr data-category-id="<?= htmlspecialchars($item['category_id'] ?? '') ?>">
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <img src="<?= htmlspecialchars($item['image'] ?? '../../views/assets/images/default.png') ?>"
                                                alt="Image of <?= htmlspecialchars($item['product_name'] ?? 'Unknown') ?>"
                                                width="50" loading="lazy">
                                        </td>
                                        <td><?= htmlspecialchars($item['product_name'] ?? 'Unknown') ?></td>
                                        <td><span class="quantity"><?= htmlspecialchars($quantity) ?></span></td>
                                        <td>
                                            <span class="quantity-text <?= strtolower($status) ?>" data-translate-key="<?= $status ?>"><?= $status ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="update-quantity" id="updateQuantitySection" style="display: none;">
                            <h3 data-translate-key="Update Quantity">Update Quantity</h3>
                            <button class="btn btn-success" onclick="updateQuantities()" data-translate-key="Update Selected Quantities">Update Selected Quantities</button>
                        </div>
                    </div>
                </div>

                <!-- Modal for Low Stock Details -->
                <div class="modal fade" id="lowStockModal" tabindex="-1" aria-labelledby="lowStockModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="lowStockModalLabel" data-translate-key="Low Stock Products">Low Stock Products</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <ul id="lowStockList" class="list-group">
                                    <!-- Populated by JavaScript -->
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-translate-key="Close">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .quantity-text {
                        display: inline-block;
                        padding: 4px 10px;
                        font-size: 14px;
                        font-weight: 500;
                        border-radius: 20px;
                    }

                    .quantity-text.high {
                        background-color: #d4edda;
                        color: #155724;
                    }

                    .quantity-text.medium {
                        background-color: #fff3cd;
                        color: #856404;
                    }

                    .quantity-text.low {
                        background-color: #f8d7da;
                        color: #721c24;
                    }

                    .notification-icon {
                        position: relative;
                        cursor: pointer;
                        display: inline-flex;
                        align-items: center;
                    }

                    .notification-icon.ring::after {
                        content: attr(data-low-stock-count);
                        position: absolute;
                        top: -12px;
                        right: -12px;
                        width: 22px;
                        height: 22px;
                        border-radius: 50%;
                        background-color: #dc3545;
                        color: white;
                        font-size: 12px;
                        font-weight: bold;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: 2px solid white;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                        animation: ring 1.5s infinite;
                        z-index: 10;
                    }

                    @keyframes ring {
                        0% {
                            transform: scale(1);
                            opacity: 1;
                        }

                        50% {
                            transform: scale(1.2);
                            opacity: 0.7;
                        }

                        100% {
                            transform: scale(1);
                            opacity: 1;
                        }
                    }

                    .low-stock-item {
                        display: flex;
                        align-items: center;
                        padding: 10px;
                    }

                    .low-stock-item img {
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                        margin-right: 10px;
                    }

                    .notification-icon[data-low-stock-count="10"]::after,
                    .notification-icon[data-low-stock-count="11"]::after,
                    .notification-icon[data-low-stock-count="12"]::after,
                    .notification-icon[data-low-stock-count="13"]::after,
                    .notification-icon[data-low-stock-count="14"]::after,
                    .notification-icon[data-low-stock-count="15"]::after,
                    .notification-icon[data-low-stock-count="16"]::after,
                    .notification-icon[data-low-stock-count="17"]::after,
                    .notification-icon[data-low-stock-count="18"]::after,
                    .notification-icon[data-low-stock-count="19"]::after {
                        width: 24px;
                        height: 24px;
                        font-size: 11px;
                    }

                    .notification-icon[data-low-stock-count="20"]::after {
                        content: '20+';
                        width: 26px;
                        height: 26px;
                        font-size: 10px;
                    }
                </style>

                <!-- JavaScript -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                
                <script>
                    // Pass low stock items from PHP to JavaScript
                    const lowStockItems = <?= json_encode($lowStockItems, JSON_NUMERIC_CHECK) ?>;

                    // Update notification count and ring effect
                    function updateNotification() {
                        console.log('Low Stock Items:', lowStockItems);
                        const notificationIcon = document.getElementById('notification-icon');
                        const notificationCount = document.getElementById('notification-count');
                        if (!notificationIcon || !notificationCount) {
                            console.error('Notification elements not found');
                            return;
                        }
                        const lowStockCount = lowStockItems.length;
                        console.log('Low Stock Count:', lowStockCount);
                        notificationCount.textContent = lowStockCount;
                        notificationIcon.setAttribute('data-low-stock-count', lowStockCount);
                        notificationIcon.setAttribute('aria-label', `Notifications: ${lowStockCount} low stock items`);
                        if (lowStockCount > 0) {
                            notificationIcon.classList.add('ring');
                        } else {
                            notificationIcon.classList.remove('ring');
                        }
                    }

                    // Populate low stock modal
                    function populateLowStockModal() {
                        const lowStockList = document.getElementById('lowStockList');
                        lowStockList.innerHTML = '';
                        if (lowStockItems.length === 0) {
                            const li = document.createElement('li');
                            li.className = 'list-group-item';
                            li.setAttribute('data-translate-key', 'No low stock products');
                            li.textContent = translations[currentLanguage]['No low stock products'] || 'No low stock products';
                            lowStockList.appendChild(li);
                            return;
                        }
                        lowStockItems.forEach(item => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item low-stock-item';
                            li.innerHTML = `
                                <img src="${item.image}" alt="${item.product_name}">
                                <div>
                                    <strong>${item.product_name}</strong><br>
                                    <span data-translate-key="Quantity">Quantity</span>: ${item.quantity}<br>
                                    <span data-translate-key="Category">Category</span>: ${item.category_name}
                                </div>
                            `;
                            lowStockList.appendChild(li);
                        });
                    }

                    // Show modal on notification icon click
                    document.addEventListener('click', (event) => {
                        if (event.target.closest('#notification-icon')) {
                            populateLowStockModal();
                            const modal = new bootstrap.Modal(document.getElementById('lowStockModal'));
                            modal.show();
                        }
                    });

                    // Filter table by category
                    function filterTable() {
                        const categoryId = document.getElementById('categorySelect').value;
                        const rows = document.querySelectorAll('.table tbody tr');
                        rows.forEach(row => {
                            row.style.display = categoryId === '' || row.dataset.categoryId === categoryId ? '' : 'none';
                        });
                    }

                    // Initialize on DOM content loaded
                    document.addEventListener('DOMContentLoaded', () => {
                        console.log('DOM fully loaded');
                        updateNotification();
                        filterTable();
                    });
                </script>
            </div>
        </div>
    </div>
</body>