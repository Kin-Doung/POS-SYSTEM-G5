<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php' ?>



<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="search-container" style="background-color: #fff;">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>
        <div class="icons">
            <i class="fas fa-globe icon-btn"></i>
            <div class="icon-btn" id="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notification-count">8</span>
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


    <div class="container table-inventory">
        <div class="orders">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="font-weight: bold;" class="purchase-head">Stock Tracking</h2>
            </div>
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control input-group-search" placeholder="Search...">
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
                    <?php foreach ($tracking as $index => $item): ?>
                        <tr data-category-id="<?= htmlspecialchars($item['category_id']); ?>">
                            <td><?= $index + 1 ?></td>
                            <td>
                                <!-- Display image for inventory item -->
                                <img src="<?= htmlspecialchars($item['image']) ?>"
                                    alt="Image of <?= htmlspecialchars($item['product_name']) ?>"
                                    style="width: 40px; height: 40px; border-radius: 100%;">
                            </td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><span class="quantity"><?= htmlspecialchars($item['quantity']) ?></span></td>
                            <td>
                                <?php
                                $status = '';
                                $quantity = $item['quantity'];

                                if ($quantity >= 50) {
                                    $status = 'High';
                                } elseif ($quantity >= 10) {
                                    $status = 'Medium';
                                } else {
                                    $status = 'Low';
                                }
                                ?>
                                <span class="badge badge-<?= strtolower($status); ?>"><?= $status; ?></span>
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
    <style>
        .badge-low {
            background-color: red;
            color: white;
        }

        .badge-medium {
            background-color: orange;
            color: white;
        }

        .badge-high {
            background-color: green;
            color: white;
        }
    </style>

    <!-- JavaScript for Edit Modal Population -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>