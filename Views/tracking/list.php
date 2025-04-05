<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php' ?>



<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>
    <!-- End Navbar -->

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