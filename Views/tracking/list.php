<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php' ?>

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
                            <h2 style="font-weight: bold;" class="purchase-head">Stock Tracking</h2>
                        </div>
                        <div class="input-group">
                            <select id="categorySelect" class="ms-2 selected" onchange="filterTable()" style="border-radius: 0;">
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

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lowStockItems = [];
                                foreach ($tracking as $index => $item):
                                    $quantity = $item['quantity'];
                                    $status = $quantity >= 50 ? 'High' : ($quantity >= 10 ? 'Medium' : 'Low');
                                    if ($status === 'Low') {
                                        $lowStockItems[] = [
                                            'id' => $item['id'],
                                            'product_name' => $item['product_name'],
                                            'quantity' => $quantity,
                                            'image' => $item['image'],
                                            'category_id' => $item['category_id']
                                        ];
                                    }
                                ?>
                                    <tr data-category-id="<?= htmlspecialchars($item['category_id']) ?>">
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <img src="<?= htmlspecialchars($item['image']) ?>"
                                                alt="Image of <?= htmlspecialchars($item['product_name']) ?>"
                                                style="width: 40px; height: 40px; border-radius: 100%;">
                                        </td>
                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                        <td><span class="quantity"><?= htmlspecialchars($quantity) ?></span></td>
                                        <td>
                                            <span class="quantity-text <?= strtolower($status) ?>"><?= $status ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="update-quantity" id="updateQuantitySection" style="display: none;">
                            <h3>Update Quantity</h3>
                            <button class="btn btn-success" onclick="updateQuantities()">Update Selected Quantities</button>
                        </div>
                    </div>
                </div>

                <!-- Modal for Low Stock Details -->
                <div class="modal fade" id="lowStockModal" tabindex="-1" aria-labelledby="lowStockModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="lowStockModalLabel">Low Stock Products</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <ul id="lowStockList" class="list-group">
                                    <!-- Populated by JavaScript -->
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    }

                    .notification-icon.ring::after {
                        content: '';
                        position: absolute;
                        top: -5px;
                        right: -5px;
                        width: 10px;
                        height: 10px;
                        border-radius: 50%;
                        border: 2px solid red;
                        animation: ring 1.5s infinite;
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
                </style>

                <!-- JavaScript -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                <script>
                    // Pass low stock items from PHP to JavaScript
                    const lowStockItems = <?= json_encode($lowStockItems) ?>;

                    // Update notification count and add ring effect
                    function updateNotification() {
                        const notificationCount = document.getElementById('notification-count');
                        const notificationIcon = document.getElementById('notification-icon');
                        notificationCount.textContent = lowStockItems.length;
                        if (lowStockItems.length > 0) {
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
                            lowStockList.innerHTML = '<li class="list-group-item">No low stock products.</li>';
                            return;
                        }
                        lowStockItems.forEach(item => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item low-stock-item';
                            li.innerHTML = `
                                <img src="${item.image}" alt="${item.product_name}">
                                <div>
                                    <strong>${item.product_name}</strong><br>
                                    Quantity: ${item.quantity}<br>
                                    Category ID: ${item.category_id}
                                </div>
                            `;
                            lowStockList.appendChild(li);
                        });
                    }

                    // Show modal on notification icon click
                    document.getElementById('notification-icon').addEventListener('click', () => {
                        populateLowStockModal();
                        const modal = new bootstrap.Modal(document.getElementById('lowStockModal'));
                        modal.show();
                    });

                    // Filter table by category
                    function filterTable() {
                        const categoryId = document.getElementById('categorySelect').value;
                        const rows = document.querySelectorAll('.table tbody tr');
                        rows.forEach(row => {
                            row.style.display = categoryId === '' || row.dataset.categoryId === categoryId ? '' : 'none';
                        });
                    }

                    // Initialize on page load
                    window.onload = () => {
                        updateNotification();
                        filterTable(); // Apply any default filter
                    };
                </script>
            </div>
        </div>
    </div>
    