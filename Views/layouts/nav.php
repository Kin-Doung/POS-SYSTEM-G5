<?php
// views/layouts/nav.php
?>
<!-- START ADDITION: Enhance lowStockItems with full details -->
<?php
// Fallback to fetch inventory if not provided
if (!isset($tracking)) {
    require_once __DIR__ . '/../../Models/TrackingModel.php';
    $trackingModel = new TrackingModel();
    $tracking = $trackingModel->getInventory();
}

// Prepare low stock items with full details
$lowStockItems = [];
foreach ($tracking as $item) {
    if ((int)$item['quantity'] < 10) {
        $lowStockItems[] = [
            'id' => $item['id'] ?? '',
            'product_name' => $item['product_name'] ?? 'Unknown',
            'quantity' => (int)($item['quantity'] ?? 0),
            'image' => $item['image'] ?? '',
            'category_id' => $item['category_id'] ?? '',
            'category_name' => $item['category_name'] ?? 'N/A', // Added from join
            'amount' => $item['amount'] ?? 0,
            'total_price' => $item['total_price'] ?? 0,
            'expiration_date' => $item['expiration_date'] ?? 'N/A'
        ];
    }
}
?>
<!-- END ADDITION -->

<nav class="navbar">
    <div class="navbar-title" data-translate-key="Welcome to Engly Store">Welcome to Engly Store</div>
    <div class="search-container">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" data-translate-key="Search" placeholder="Search">
    </div>
    <div class="icons">
        <!-- Language Dropdown -->
        <div class="dropdown">
            <button class="icon-btn dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Select language" style="background-color: #fff;">
                <i class="fas fa-globe"></i>
                <span id="currentLanguage" style="margin-left: 5px;">English</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                <li><a class="dropdown-item" href="#" data-lang="en" data-translate-key="English">English</a></li>
                <li><a class="dropdown-item" href="#" data-lang="km" data-translate-key="Khmer">ខ្មែរ (Khmer)</a></li>
            </ul>
        </div>
        <!-- Notification Dropdown for Low Stock -->
        <div class="dropdown">
            <div class="icon-btn" id="notification-icon" data-bs-toggle="dropdown" aria-expanded="false" data-low-stock-count="0" aria-label="Notifications: 0 low stock items">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notification-count">0</span>
            </div>
            <div class="dropdown-menu dropdown-menu-end low-stock-card" aria-labelledby="notification-icon">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title" data-translate-key="Low Stock Alerts">Low Stock Alerts</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th style="display: none;">Category</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="lowStockTableBody">
                                <?php
                                if (!empty($lowStockItems)) {
                                    foreach ($lowStockItems as $index => $item):
                                ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <img src="<?= htmlspecialchars($item['image'] ?? '../../views/assets/images/default.png') ?>"
                                                    alt="Image of <?= htmlspecialchars($item['product_name'] ?? 'Unknown') ?>"
                                                    style="width: 40px; height: 40px; border-radius: 100%;">
                                            </td>
                                            <td><?= htmlspecialchars($item['product_name'] ?? 'Unknown') ?></td>
                                            <td style="display: none;"><?= htmlspecialchars($item['category_id'] ?? 'N/A') ?></td>
                                            <td class="low-quantity"><?= htmlspecialchars($item['quantity'] ?? 0) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center;">No low stock items.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="cart-icon" id="cartToggle">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count" id="cartCount"></span>
        </div>
    </div>
    <div class="profile">
        <img src="../../views/assets/images/image.png" alt="User">
        <div class="profile-info">
            <span id="profile-name">Eng Ly</span>
            <span class="store-name" id="store-name" data-translate-key="Owner Store">Owner Store</span>
        </div>
        <ul class="menu" id="menu">
            <li><a href="/settings" class="item" data-translate-key="Account">Account</a></li>
            <li><a href="/settings" class="item" data-translate-key="Setting">Setting</a></li>
            <li><a href="/logout" class="item" data-translate-key="Logout">Logout</a></li>
        </ul>
    </div>

    <table class="table" style="display: none;">
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
            $tracking = $tracking ?? [];
            $lowStockItems = [];
            if (!empty($tracking)) {
                foreach ($tracking as $index => $item):
                    $quantity = (int)($item['quantity'] ?? 0);
                    $status = $quantity >= 50 ? 'High' : ($quantity >= 10 ? 'Medium' : 'Low');
                    if ($status === 'Low') {
                        $lowStockItems[] = [
                            'id' => $item['id'] ?? '',
                            'product_name' => $item['product_name'] ?? 'Unknown',
                            'quantity' => $quantity,
                            'image' => $item['image'] ?? '',
                            'category_id' => $item['category_id'] ?? ''
                        ];
                    }
            ?>
                    <tr data-category-id="<?= htmlspecialchars($item['category_id'] ?? '') ?>">
                        <td><?= $index + 1 ?></td>
                        <td>
                            <img src="<?= htmlspecialchars($item['image'] ?? '../../views/assets/images/default.png') ?>"
                                alt="Image of <?= htmlspecialchars($item['product_name'] ?? 'Unknown') ?>"
                                style="width: 40px; height: 40px; border-radius: 100%;">
                        </td>
                        <td><?= htmlspecialchars($item['product_name'] ?? 'Unknown') ?></td>
                        <td><span class="quantity"><?= htmlspecialchars($quantity) ?></span></td>
                        <td>
                            <span class="quantity-text <?= strtolower($status) ?>"><?= $status ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No stock tracking data available.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- START ADDITION: Low Stock Detail Modal -->
    <div class="modal fade" id="lowStockDetailModal" tabindex="-1" aria-labelledby="lowStockDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lowStockDetailModalLabel" data-translate-key="Low Stock Details">Low Stock Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table" style="display: none;">
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
                            $tracking = $tracking ?? [];
                            $lowStockItems = [];
                            if (!empty($tracking)) {
                                foreach ($tracking as $index => $item):
                                    $quantity = (int)($item['quantity'] ?? 0);
                                    $status = $quantity >= 50 ? 'High' : ($quantity >= 10 ? 'Medium' : 'Low');
                                    if ($status === 'Low') {
                                        $lowStockItems[] = [
                                            'id' => $item['id'] ?? '',
                                            'product_name' => $item['product_name'] ?? 'Unknown',
                                            'quantity' => $quantity,
                                            'image' => $item['image'] ?? '',
                                            'category_id' => $item['category_id'] ?? ''
                                        ];
                                    }
                            ?>
                                    <tr data-category-id="<?= htmlspecialchars($item['category_id'] ?? '') ?>">
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <img src="<?= htmlspecialchars($item['image'] ?? '../../views/assets/images/default.png') ?>"
                                                alt="Image of <?= htmlspecialchars($item['product_name'] ?? 'Unknown') ?>"
                                                style="width: 40px; height: 40px; border-radius: 100%;">
                                        </td>
                                        <td><?= htmlspecialchars($item['product_name'] ?? 'Unknown') ?></td>
                                        <td><span class="quantity"><?= htmlspecialchars($quantity) ?></span></td>
                                        <td>
                                            <span class="quantity-text <?= strtolower($status) ?>"><?= $status ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">No stock tracking data available.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END ADDITION -->

</nav>


<script>
    
    
    document.addEventListener('DOMContentLoaded', function() {
        // Update notification count and debug low stock items
        const lowStockItems = <?php echo json_encode($lowStockItems); ?>;
        const notificationCount = document.getElementById('notification-count');
        if (notificationCount) {
            notificationCount.textContent = lowStockItems.length;
            console.log('Low stock items:', lowStockItems);
            console.log('Notification count set to:', lowStockItems.length);
        } else {
            console.error('Notification count element not found');
        }

        // Verify Bootstrap dropdown functionality
        const notificationIcon = document.getElementById('notification-icon');
        const lowStockCard = document.querySelector('.low-stock-card');
        if (notificationIcon) {
            if (typeof bootstrap !== 'undefined') {
                console.log('Bootstrap dropdown initialized for notification');
                notificationIcon.addEventListener('click', function() {
                    console.log('Notification icon clicked, dropdown toggled');
                });
            } else {
                console.error('Bootstrap JS not loaded. Dropdown will not work.');
            }
        } else {
            console.error('Notification icon not found');
        }

        if (lowStockCard) {
            lowStockCard.addEventListener('show.bs.dropdown', function() {
                console.log('Low stock dropdown shown');
            });
            lowStockCard.addEventListener('hide.bs.dropdown', function() {
                console.log('Low stock dropdown hidden');
            });
        }

        // Search functionality
        const searchInput = document.querySelector('#searchInput');
        const searchIcon = document.querySelector('.search-container .fa-search');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                filterProducts();
            });
        }

        if (searchIcon) {
            searchIcon.addEventListener('click', filterProducts);
        }

        function filterProducts() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            const tableRows = document.querySelectorAll('.table tbody tr');

            tableRows.forEach(row => {
                const productNameCell = row.cells[2]; // Product name in third column
                if (productNameCell) {
                    const productName = productNameCell.textContent.trim().toLowerCase();
                    row.style.display = productName.includes(searchTerm) ? '' : 'none';
                }
            });

            if (searchTerm === '') {
                tableRows.forEach(row => {
                    row.style.display = '';
                });
            }
        }


    });
</script>

<!-- START ADDITION: Modal-specific styles -->
<style>
    .modal-lg {
        max-width: 800px;
    }

    .table-sm th,
    .table-sm td {
        padding: 0.5rem;
        font-size: 0.9rem;
    }

    .table img {
        object-fit: cover;
    }

    .low-quantity {
        color: red;
        font-weight: bold;
    }
</style>
<!-- END ADDITION -->
