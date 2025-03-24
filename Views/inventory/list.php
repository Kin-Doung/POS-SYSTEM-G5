<?php require_once './views/layouts/side.php'; ?>
<?php require_once './views/layouts/header.php'; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Main Content Area -->
    <div class="content-area" style="margin-left: 250px; padding: 20px; width: calc(100% - 250px); background:white;">
        <!-- Navbar -->
        <div class="navbar mt-n1">
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
                    <span class="user-name" style="color: black;">Engly</span>
                    <span class="store-name">Engly Store</span>
                </div>
            </div>

            <!-- Sidenav toggle for small screens -->
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </a>
            </li>
        </div>

        <!-- Product Overview Cards -->
        <div class="mt-4">
            <div class="row d-flex justify-content-center">
                <!-- In Stock Card -->
                <div class="col-12 col-sm-3 mb-3">
                    <div class="card shadow-sm border-success position-relative" style="height: 120px;">
                        <div class="card-body text-center d-flex flex-column justify-content-start mt-n4">
                            <i class="fas fa-box-open fa-2x text-success mb-2"></i>
                            <h5 class="card-title mb-1">In Stock</h5>
                            <p class="card-text mb-0">Items available!</p>
                        </div>
                        <div class="position-absolute top-0 end-0 p-3 text-success fs-4">
                            <strong>20</strong>
                        </div>
                    </div>
                </div>

                <!-- Out of Stock Card -->
                <div class="col-12 col-sm-3 mb-3">
                    <div class="card shadow-sm border-danger position-relative" style="height: 120px;">
                        <div class="card-body text-center d-flex flex-column justify-content-start mt-n4">
                            <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                            <h5 class="card-title mb-1">Out of Stock</h5>
                            <p class="card-text mb-0">Currently unavailable.</p>
                        </div>
                        <div class="position-absolute top-0 end-0 p-3 text-danger fs-4">
                            <strong>0</strong>
                        </div>
                    </div>
                </div>

                <!-- Full Stock Card -->
                <div class="col-12 col-sm-3 mb-3">
                    <div class="card shadow-sm border-primary position-relative" style="height: 120px;">
                        <div class="card-body text-center d-flex flex-column justify-content-start mt-n4">
                            <i class="fas fa-cogs fa-2x text-primary mb-2"></i>
                            <h5 class="card-title mb-1">Full Stock</h5>
                            <p class="card-text mb-0">Fully replenished stock!</p>
                        </div>
                        <div class="position-absolute top-0 end-0 p-3 text-primary fs-4">
                            <strong>50</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Table -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 ms-3">Product Table</h2>
            <a href="/inventory/create" class="btn btn-primary add-stock">Add Stock</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Image</th> <!-- Add Image Column -->
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>Expiration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventoryItems as $item): ?>
                        <tr>
                            <!-- Display Image Column -->
                            <td>
                                <?php if (!empty($item['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="Product Image" style="width: 50px; height: 50px;">
                                <?php else: ?>
                                    <img src="default-image.jpg" alt="Default Image" style="width: 50px; height: 50px;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($item['amount']); ?></td>
                            <td><?php echo htmlspecialchars($item['expiration_date']); ?></td>

                            <td>
                                <div class="dropdown">
                                    <button class="see-more-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        see more
                                    </button>
                                    <ul class="dropdown-menu">
                                        <!-- View Action: Opens the View Modal -->
                                        <li><a class="dropdown-item text-dark" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $item['id']; ?>"><i class="fa-solid fa-eye"></i> View</a></li>
                                        <!-- Edit Action: Opens the Edit Modal -->
                                        <li><a class="dropdown-item text-dark" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $item['id']; ?>"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>
                                        <li><a class="dropdown-item text-dark" href="/inventory/destroy?id=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="fa-solid fa-trash" style="color: red;"></i> Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal for View -->
        <?php foreach ($inventoryItems as $item): ?>
            <div class="modal fade" id="viewModal<?php echo $item['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $item['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel<?php echo $item['id']; ?>">Product Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Product Name:</strong> <?php echo htmlspecialchars($item['product_name']); ?></p>
                            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($item['quantity']); ?></p>
                            <p><strong>Amount:</strong> <?php echo htmlspecialchars($item['amount']); ?></p>
                            <p><strong>Expiration Date:</strong> <?php echo htmlspecialchars($item['expiration_date']); ?></p>

                            <?php if ($item['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="Product Image" style="width: 150px;">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Modal for Edit -->
        <?php foreach ($inventoryItems as $item): ?>
            <div class="modal fade" id="editModal<?php echo $item['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $item['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel<?php echo $item['id']; ?>">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/inventory/update?id=<?php echo $item['id']; ?>" method="POST">
                                <div class="mb-3">
                                    <label for="product_name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($item['product_name']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars($item['amount']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="expiration_date" class="form-label">Expiration Date</label>
                                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="<?php echo htmlspecialchars($item['expiration_date']); ?>">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</main>


