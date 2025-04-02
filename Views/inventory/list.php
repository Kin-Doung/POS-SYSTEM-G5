<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="search-container">
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
                <h2 style="font-weight: bold;" class="purchase-head">Restock Products</h2>
                <div>
                    <a href="/inventory/create" class="btn-new-product">
                        <i class="bi-plus-lg"></i> + Add Stocks
                    </a>
                </div>
            </div>
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control input-group-search" placeholder="Search...">
                <select id="categorySelect" class="ms-2 selected" onchange="filterTable()">
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
                        <th>Price</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory as $index => $item): ?>
                        <tr data-category-id="<?= htmlspecialchars($item['category_id']); ?>">
                            <td><?= $index + 1 ?></td>
                            <td>
                                <!-- Display image for inventory item -->
                                <img src="<?= htmlspecialchars($item['image']) ?>"
                                    alt="Image of <?= htmlspecialchars($item['product_name']) ?>"
                                    style="width: 40px; height: 40px; border-radius: 100%;">
                            </td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><span class="quantity-text"><?= htmlspecialchars($item['quantity']) ?></span></td>
                            <td>$<?= htmlspecialchars(number_format($item['amount'], 2)) ?></td>
                            <td>$<?= htmlspecialchars(number_format($item['total_price'], 2)) ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn-seemore dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        See more...
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#viewModal<?= $item['id']; ?>"><i class="fa-solid fa-eye"></i> View</a></li>
                                        <li>
                                            <a class="dropdown-item text-dark" href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-id="<?= $item['id'] ?>"
                                                data-product_name="<?= htmlspecialchars($item['product_name']) ?>"
                                                data-category_id="<?= $item['category_id'] ?>"
                                                data-quantity="<?= $item['quantity'] ?>"
                                                data-amount="<?= $item['amount'] ?>"
                                                data-expiration_date="<?= $item['expiration_date'] ?>"
                                                data-image="<?= htmlspecialchars($item['image']) ?>"> <!-- Ensure image data is properly escaped -->
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </a>
                                        </li>
                                        <li><a class="dropdown-item text-dark" href="/inventory/delete?id=<?= $item['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa-solid fa-trash"></i> Delete</a></li>
                                    </ul>
                                </div>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal<?= $item['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content rounded-4 shadow-lg">
                                            <div class="modal-header">
                                                <h2 class="modal-title">View Inventory Item</h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body d-flex justify-content-between align-items-center">
                                                <div class="text-start detail">
                                                    <p><strong>Product Name:</strong> <?= htmlspecialchars($item['product_name']); ?></p>
                                                    <p><strong>Category:</strong> <?= !empty($item['category_name']) ? htmlspecialchars($item['category_name']) : '-'; ?></p>
                                                    <p><strong>Quantity:</strong> <?= htmlspecialchars($item['quantity']); ?></p>
                                                    <p><strong>Price:</strong> $<?= htmlspecialchars(number_format($item['amount'], 2)); ?></p>
                                                    <p><strong>Total Price:</strong> $<?= htmlspecialchars(number_format($item['total_price'], 2)); ?></p>
                                                    <p><strong>Expiration Date:</strong> <?= htmlspecialchars($item['expiration_date']); ?></p>
                                                </div>
                                                <?php if (!empty($item['image'])): ?>
                                                    <div class="mb-3">
                                                        <img src="<?= htmlspecialchars($item['image']); ?>" alt="Product Image" width="150" class="img-fluid">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/inventory/update?id=" method="POST" enctype="multipart/form-data">
                                                    <div class="mb-3">
                                                        <label class="form-label">Product Name</label>
                                                        <input type="text" class="form-control" name="product_name" id="product_name" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Category</label>
                                                            <select class="form-control" name="category_id" id="category_id" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($categories ?? [] as $category): ?>
                                                                    <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Quantity</label>
                                                            <input type="number" class="form-control" name="quantity" id="quantity" required min="1">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Amount</label>
                                                            <input type="number" class="form-control" name="amount" id="amount" required step="0.01" min="0">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Expiration Date</label>
                                                            <input type="date" class="form-control" name="expiration_date" id="expiration_date" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Image</label>
                                                        <input type="file" class="form-control" id="imageInput" name="image" accept="image/*">
                                                        <img id="imagePreview" src="" alt="Product Image" width="50" height="50" class="rounded-circle mt-2">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

    <!-- JavaScript for Edit Modal Population -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* General Reset and Base Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }
        .container{
            width: 100%;
            margin-top: 10px;
        }

        .search-container {
            display: flex;
            align-items: center;
            background-color: #f1f3f5;
            padding: 8px 15px;
            border-radius: 20px;
            width: 300px;
        }

        .search-container i {
            margin-right: 10px;
            color: #6c757d;
        }

        .search-container input {
            border: none;
            background: none;
            outline: none;
            width: 100%;
            font-size: 14px;
        }

        .icons {
            display: flex;
            gap: 20px;
        }

        .icon-btn {
            position: relative;
            cursor: pointer;
            font-size: 18px;
            color: #6c757d;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
        }

        #profile-name {
            font-weight: bold;
            font-size: 16px;
        }

        .store-name {
            font-size: 12px;
            color: #6c757d;
        }

        .menu {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            list-style: none;
            padding: 10px 0;
        }

        .menu li a {
            display: block;
            padding: 8px 20px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }

        .menu li a:hover {
            background-color: #f1f3f5;
        }

        .profile:hover .menu {
            display: block;
        }

        /* Main Content */
        .main-content {
            padding: 20px;
        }

        .table-inventory {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .purchase-head {
            font-size: 24px;
            color: #2c3e50;
        }

        .btn-new-product {
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-new-product:hover {
            background-color: #218838;
            color: white;
        }

        .input-group {
            margin: 20px 0;
            display: flex;
            gap: 10px;
        }

        .input-group-search {
            border: 1px solid #ced4da;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 14px;
        }

        .selected {
            border: 1px solid #ced4da;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 14px;
            background-color: #ffffff;
        }

        /* Table Styling */


        .table thead th {
            background-color: #2c3e50;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        .table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.2s;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table td {
            padding: 12px;
            vertical-align: middle;
        }

        .table img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .quantity-text {
            font-weight: bold;
            color: #495057;
        }

        .btn-seemore {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            cursor: pointer;
        }

        .btn-seemore:hover {
            background-color: #0056b3;
        }

        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-size: 14px;
        }

        .dropdown-item i {
            margin-right: 8px;
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 12px;
            padding: 20px;
        }

        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }

        .modal-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
        }

        .modal-body {
            padding: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Low Stock and Out of Stock Indicators */
        .low-stock {
            background-color: #fff3cd;
            font-weight: bold;
        }

        .out-of-stock {
            background-color: #f8d7da;
            font-weight: bold;
        }



        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .search-container {
                width: 100%;
            }

            .table {
                font-size: 12px;
            }

            .btn-new-product {
                width: 100%;
                text-align: center;
            }

            .input-group {
                flex-direction: column;
            }
        }
    </style>
</main>