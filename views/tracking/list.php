<?php
// stock_tracking.php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
<!-- Include Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" defer></script>
<style>
    .purchase-head {
        font-family: "Poppins", sans-serif;
    }

    .selected {
        border-radius: 8px !important;
        margin-left: 33px !important;
    }

    /* Style for the chart and message layout */
    .chart-message-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 50px;
        margin: 20px 0;
    }

    .chart-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .chart-container {
        width: 350px;
        height: 350px;
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 15px;
    }

    .quantities-display {
        margin-top: 15px;
        display: flex;
        justify-content: space-around;
        width: 350px;
        font-family: "Poppins", sans-serif;
    }

    .quantity-item {
        display: flex;
        align-items: center;
        font-size: 14px;
        font-weight: 500;
    }

    .quantity-item span.color-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .quantity-item.high .color-dot {
        background-color: #34C759;
        /* Modern green for High */
    }

    .quantity-item.medium .color-dot {
        background-color: #FFCC00;
        /* Modern yellow for Medium */
    }

    .quantity-item.low .color-dot {
        background-color: #FF3B30;
        /* Modern red for Low */
    }

    /* Style for the message box */
    .stock-message {
        max-width: 400px;
        background-color: #fff;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        font-family: "Poppins", sans-serif;
        width: 400px;
        height: 55vh;
        margin-top: -13px;
    }

    .stock-message h4 {
        font-size: 18px;
        /* text-shadow: 0 2px 7px gray ; */
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .stock-message ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .stock-message li {
        font-size: 14px;
        margin-bottom: 8px;
        color: #555;
    }

    .stock-message li span.product-name {
        font-weight: 500;
    }

    .stock-message li span.stock-level.high {
        color: #34C759;
    }

    .stock-message li span.stock-level.medium {
        color: #FFCC00;
    }

    .stock-message li span.stock-level.low {
        color: #FF3B30;
    }
</style>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" style="margin-left: 250px;">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php require_once './views/layouts/nav.php' ?>
                <div class="container table-inventory" style="background-color: #fff; height:auto; box-shadow:none">
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
                                    $quantity = (int)$item['quantity']; // Ensure integer
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
                                        <td><img src="<?= $item['image'] ?>" alt="Product Image" width="50" loading="lazy"></td>
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
                    <!-- Chart and Message Layout -->
                    <div class="chart-message-wrapper">
                    <div class="stock-message" id="stockMessage">
                            <h4>Stock Levels</h4>
                            <ul>
                                <!-- Product messages will be populated here -->
                            </ul>
                        </div>
                        <div class="chart-wrapper">
                            <div class="chart-container">
                                <canvas id="stockLevelChart"></canvas>
                            </div>
                            <div class="quantities-display" id="quantitiesDisplay">
                                <!-- Quantities will be populated here -->
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
                    document.addEventListener('click', (event) => {
                        if (event.target.closest('#notification-icon')) {
                            populateLowStockModal();
                            const modal = new bootstrap.Modal(document.getElementById('lowStockModal'));
                            modal.show();
                        }
                    });

                    // Filter table by category and update chart
                    function filterTable() {
                        const categoryId = document.getElementById('categorySelect').value;
                        const rows = document.querySelectorAll('.table tbody tr');
                        rows.forEach(row => {
                            row.style.display = categoryId === '' || row.dataset.categoryId === categoryId ? '' : 'none';
                        });
                        updatePieChart();
                    }

                    // Function to create/update the pie chart, quantities display, and stock message
                    function updatePieChart() {
                        const rows = document.querySelectorAll('.table tbody tr:not([style*="display: none"])');
                        let highCount = 0,
                            mediumCount = 0,
                            lowCount = 0;
                        const stockDetailsMap = new Map(); // Use Map to store unique products

                        // Count stock levels and collect unique product details
                        rows.forEach(row => {
                            const quantity = parseInt(row.querySelector('.quantity').textContent.trim());
                            const productName = row.querySelector('td:nth-child(3)').textContent.trim();
                            let status;

                            if (quantity >= 50) {
                                status = 'High';
                                highCount++;
                            } else if (quantity >= 10) {
                                status = 'Medium';
                                mediumCount++;
                            } else {
                                status = 'Low';
                                lowCount++;
                            }

                            // Store product details in Map to avoid duplicates
                            if (!stockDetailsMap.has(productName)) {
                                stockDetailsMap.set(productName, {
                                    productName: productName,
                                    status: status,
                                    quantity: quantity
                                });
                            }
                        });

                        // Convert Map to array for rendering
                        const stockDetails = Array.from(stockDetailsMap.values());

                        // Update the quantities display below the chart
                        const quantitiesDisplay = document.getElementById('quantitiesDisplay');
                        quantitiesDisplay.innerHTML = `
                            <div class="quantity-item high">
                                <span class="color-dot"></span> High: ${highCount}
                            </div>
                            <div class="quantity-item medium">
                                <span class="color-dot"></span> Medium: ${mediumCount}
                            </div>
                            <div class="quantity-item low">
                                <span class="color-dot"></span> Low: ${lowCount}
                            </div>
                        `;

                        // Update the stock message on the right
                        const stockMessage = document.getElementById('stockMessage').querySelector('ul');
                        stockMessage.innerHTML = stockDetails.length > 0 ? stockDetails.map(item => `
                            <li>
                                <span class="product-name">${item.productName}</span> is 
                                <span class="stock-level ${item.status.toLowerCase()}">${item.status}</span>: ${item.quantity}
                            </li>
                        `).join('') : '<li>No products to display.</li>';

                        // Define chart data with modern colors
                        const data = {
                            labels: ['High', 'Medium', 'Low'],
                            datasets: [{
                                data: [highCount, mediumCount, lowCount],
                                backgroundColor: [
                                    '#34C759', // Modern green for High
                                    '#FFCC00', // Modern yellow for Medium
                                    '#FF3B30' // Modern red for Low
                                ],
                                borderColor: [
                                    '#2DAE4A', // Slightly darker green
                                    '#E6B800', // Slightly darker yellow
                                    '#E0352B' // Slightly darker red
                                ],
                                borderWidth: 2,
                                hoverOffset: 15,
                                shadowOffsetX: 3,
                                shadowOffsetY: 3,
                                shadowBlur: 10,
                                shadowColor: 'rgba(0, 0, 0, 0.2)'
                            }]
                        };

                        // Chart configuration
                        const ctx = document.getElementById('stockLevelChart').getContext('2d');
                        if (window.stockLevelChart instanceof Chart) {
                            // Update existing chart
                            window.stockLevelChart.data.datasets[0].data = [highCount, mediumCount, lowCount];
                            window.stockLevelChart.update();
                        } else {
                            // Create new chart
                            window.stockLevelChart = new Chart(ctx, {
                                type: 'pie',
                                data: data,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                            labels: {
                                                font: {
                                                    family: '"Poppins", sans-serif',
                                                    size: 14,
                                                    weight: '500'
                                                },
                                                color: '#333',
                                                padding: 15,
                                                boxWidth: 15,
                                                boxHeight: 15
                                            }
                                        },
                                        title: {
                                            display: true,
                                            text: 'Stock Level Distribution',
                                            font: {
                                                family: '"Poppins", sans-serif',
                                                size: 18,
                                                weight: 'bold'
                                            },
                                            color: '#333',
                                            padding: {
                                                top: 10,
                                                bottom: 20
                                            }
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                            titleFont: {
                                                family: '"Poppins", sans-serif',
                                                size: 14
                                            },
                                            bodyFont: {
                                                family: '"Poppins", sans-serif',
                                                size: 12
                                            },
                                            callbacks: {
                                                label: function(context) {
                                                    const label = context.label || '';
                                                    const value = context.raw || 0;
                                                    const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                                    return `${label}: ${value} (${percentage}%)`;
                                                }
                                            },
                                            padding: 10,
                                            cornerRadius: 8
                                        }
                                    },
                                    elements: {
                                        arc: {
                                            borderWidth: 2,
                                            borderAlign: 'inner'
                                        }
                                    },
                                    cutout: '10%',
                                    radius: '85%',
                                    animation: {
                                        animateScale: true,
                                        animateRotate: true
                                    }
                                },
                                plugins: [{
                                    id: 'shadowPlugin',
                                    beforeDraw: (chart) => {
                                        const ctx = chart.ctx;
                                        ctx.shadowColor = 'rgba(0, 0, 0, 0.2)';
                                        ctx.shadowBlur = 10;
                                        ctx.shadowOffsetX = 3;
                                        ctx.shadowOffsetY = 3;
                                    },
                                    afterDraw: (chart) => {
                                        chart.ctx.shadowColor = 'rgba(0, 0, 0, 0)';
                                        chart.ctx.shadowBlur = 0;
                                        chart.ctx.shadowOffsetX = 0;
                                        chart.ctx.shadowOffsetY = 0;
                                    }
                                }]
                            });
                        }
                    }

                    // Initialize on DOM content loaded
                    document.addEventListener('DOMContentLoaded', () => {
                        console.log('DOM fully loaded');
                        updateNotification();
                        filterTable();
                        updatePieChart(); // Initial chart render
                    });
                </script>
            </div>
        </div>
    </div>
</body>