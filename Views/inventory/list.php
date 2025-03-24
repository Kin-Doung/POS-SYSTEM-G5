<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar">
        <!-- Search Bar -->
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>

        <!-- Icons -->
        <div class="icons">
            <i class="fas fa-globe icon-btn"></i>
            <div class="icon-btn" id="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notification-count">8</span>
            </div>
        </div>

        <!-- Profile -->
        <div class="profile">
            <img src="../../images/image.png" alt="User">
            <div class="profile-info">
                <span>Jimmy Sullivan</span>
                <span class="store-name">Odama Store</span>
            </div>
        </div>
        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
            </a>
        </li>
    </nav>
    <!-- End Navbar -->
    <!-- /// alert fuction// -->
    <div class="content mt-4 mb-3">
        <div class="row justify-content-center">
            <!-- In Stock Card -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg" style="height: 120px;">
                    <div class="card-body text-center mt-n4">
                        <div class="position-relative">
                            <i class="fas fa-warehouse text-success" style="font-size: 2.5rem;"></i>
                            <p class="card-text position-absolute top-0 end-0 m-2" style="font-size: 1.2rem;">20</p>
                        </div>
                        <h5 class="card-title mt-4">In Stock</h5>
                    </div>
                </div>
            </div>

            <!-- Out of Stock Card -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg" style="height: 120px;">
                    <div class="card-body text-center mt-n4">
                        <div class="position-relative">
                            <i class="fas fa-times-circle text-danger" style="font-size: 2.5rem;"></i>
                            <p class="card-text position-absolute top-0 end-0 m-2" style="font-size: 1.2rem;">30</p>
                        </div>
                        <h5 class="card-title mt-4">Out of Stock</h5>
                    </div>
                </div>
            </div>

            <!-- Full Stock Card -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg" style="height: 120px;">
                    <div class="card-body text-center mt-n4">
                        <div class="position-relative">
                            <i class="fas fa-check-circle text-warning" style="font-size: 2.5rem;"></i>
                            <p class="card-text position-absolute top-0 end-0 m-2" style="font-size: 1.2rem;">50</p>
                        </div>
                        <h5 class="card-title mt-4">Full Stock</h5>
                    </div>
                </div>
            </div>


        </div>
    </div>



    <div class="container  table-inventory">
        <div class="orders">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Purchasing Orders</h2>


                <div>
                    <a href="/inventory/create" class="btn btn-secondary">
                        <i class="bi-plus-lg"></i> + New Products
                    </a>

                    <!-- <a href="/inventory/create">class="btn btn-primary">+ New Products</a> -->
                    <!-- <button class="btn btn-secondary" id="batchActionBtn" disabled>Batch Action</button> -->
                </div>
            </div>
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control input-group-search" placeholder="Search...">


                <select id="categorySelect" class=" ms-2 selected" onchange="filterTable()">
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
                        <th># </th>
                        <th>Image</th>
                        <th>Product Name </th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP Loop for Data (example) -->
                    <?php foreach ($inventory as $index => $item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($item['image']) ?>"
                                    alt="Image of <?= htmlspecialchars($item['product_name']) ?>"
                                    style="width: 40px; height: 40px; border-radius: 100%;">
                            </td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><span class="quantity-text"><?= htmlspecialchars($item['quantity']) ?></span></td>
                            <td><?= htmlspecialchars($item['amount']) ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn-seemore dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        See more...
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li> <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#viewModal<?= $item['id']; ?>"> <i class="fa-solid fa-eye"></i> View</a> </li>
                                        <li><a class="dropdown-item text-dark" href="/inventory/edit?id=<?= $item['id'] ?>"> <i class="fa-solid fa-pen-to-square"></i> Edit</a></li>
                                        <li>
                                            <a class="dropdown-item text-dark" href="/inventory/delete?id=<?= $item['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa-solid fa-trash"></i> Delete</a>
                                        </li>

                                    </ul>
                                </div>


                                <div class="modal fade" id="viewModal<?= $item['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="modal-title">View Inventory Item</h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body d-flex justify-content-between align-items-center"> <!-- Flexbox layout -->
                                                <!-- Text on the Left -->
                                                <div class="text-start d-inline-block detail "> <!-- Keeps text aligned left -->
                                                    <p><strong>Product Name:</strong> <?= htmlspecialchars($item['product_name']); ?></p>
                                                    <p><strong>Category:</strong> <?= !empty($item['category_name']) ? htmlspecialchars($item['category_name']) : '-'; ?></p>
                                                    <p><strong>Quantity:</strong> <?= htmlspecialchars($item['quantity']); ?></p>
                                                    <p><strong>Price:</strong> $<?= htmlspecialchars(number_format($item['amount'], 2)); ?></p>
                                                    <p><strong>Expiration Date:</strong> <?= htmlspecialchars($item['expiration_date']); ?></p>
                                                </div>

                                                <!-- Image on the Right -->
                                                <?php if (!empty($item['image'])): ?>
                                                    <div class="mb-3">
                                                        <img src="<?= htmlspecialchars($item['image']); ?>" alt="Product Image" width="150" class="img-fluid">
                                                    </div>
                                                <?php endif; ?>
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
</main>