<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
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
            <div class="col-lg-2.5 col-md-3 col-sm-4">
                <div class="card card-stats shadow-sm border-0 rounded-lg">
                    <div class="card-body p-2">
                        <div class="row align-items-center">
                            <div class="col-3 text-center">
                                <i class="fas fa-box text-danger" style="font-size: 2.5rem;"></i>
                            </div>
                            <div class="col-9 text-right">
                                <p class="card-category text-muted" style="font-size: 0.85rem;">Out Inventory</p>
                                <h4 class="card-title" style="font-size: 1.2rem;">12</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-2 align-items-center">
                        <hr class="my-1">
                        <div class="d-flex justify-content-center">
                            <button class="show-button d-flex align-items-center gap-2 border-0 rounded-pill py-1 px-6"
                                style="font-size: 0.7rem;">
                                <i class="fas fa-caret-down"></i> show
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-2.5 col-md-3 col-sm-4">
                <div class="card card-stats shadow-sm border-0 rounded-lg">
                    <div class="card-body p-2">
                        <div class="row align-items-center">
                            <div class="col-3 text-center">
                                <i class="fas fa-box text-warning" style="font-size: 2.5rem;"></i>
                            </div>
                            <div class="col-9 text-right">
                                <p class="card-category text-muted" style="font-size: 0.85rem;">Low Inventory</p>
                                <h4 class="card-title" style="font-size: 1.2rem;">12</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-2 align-items-center">
                        <hr class="my-1">
                        <div class="d-flex justify-content-center">
                            <button class="show-button d-flex align-items-center gap-2 border-0 rounded-pill py-1 px-6"
                                style="font-size: 0.7rem;">
                                <i class="fas fa-caret-down"></i> show
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2.5 col-md-3 col-sm-4">
                <div class="card card-stats shadow-sm border-0 rounded-lg">
                    <div class="card-body p-2">
                        <div class="row align-items-center">
                            <div class="col-3 text-center">
                                <i class="fas fa-box text-success" style="font-size: 2.5rem;"></i>
                            </div>
                            <div class="col-9 text-right">
                                <p class="card-category text-muted" style="font-size: 0.85rem;">Full Inventory</p>
                                <h4 class="card-title" style="font-size: 1.2rem;">12</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-2 align-items-center">
                        <hr class="my-1">
                        <div class="d-flex justify-content-center">
                            <button class="show-button d-flex align-items-center gap-2 border-0 rounded-pill py-1 px-6"
                                style="font-size: 0.7rem;">
                                <i class="fas fa-caret-down"></i> show
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <title>Purchasing Orders</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->


    <div class="container mt-4" style="width: 95%;">
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
                <input type="text" id="searchInput" class="form-control" placeholder="Search...">

                <select id="categorySelect" class=" ms-2" onchange="filterTable()">
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
                <thead class="bg-dark text-wite">
                    <tr>
                        <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"></th>
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
                        <tr data-id="<?= $item['id'] ?>"> <!-- Add data-id attribute here -->
                            <td><input type="checkbox" onclick="toggleEdit(this); toggleUpdateButton();"></td>
                            <td>
                                <img src="<?= htmlspecialchars($item['image']) ?>"
                                    alt="Image of <?= htmlspecialchars($item['product_name']) ?>"
                                    style="width: 40px; height: 40px; border-radius: 100%;">
                            </td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><input type="number" class="form-control quantity-input" style="width: 80px;" value="<?= htmlspecialchars($item['quantity']) ?>" min="0" onchange="updatePrice(this)" disabled></td>
                            <td><input type="text" class="form-control price-input" style="width: 80px;" value="<?= htmlspecialchars($item['amount']) ?>" readonly></td>
                            <td><input type="number" class="form-control total-input" style="width: 80px;" value="<?= htmlspecialchars($item['total_price']) ?>" min="0" onchange="updatePrice(this)" disabled></td>
                            <td>
                                <div class="dropdown">
                                    <button class="dropbtn" onclick="toggleDropdown(event)">...</button>
                                    <div class="dropdown-content">
                                        <a href="/inventory/edit?id=<?= $item['id'] ?>" class="btn-warning">Edit</a>
                                        <a href="/inventory/delete?id=<?= $item['id'] ?>" class="bg-danger text-white">Delete</a>
                                        <a href="#">View Details</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
            <div class="update-quantity mt-4" id="updateButtonContainer" style="display: none;">
                <h3>Update Quantity</h3>
                <button class="btn btn-success" onclick="releaseCheckedCheckboxes()">Update Selected Quantities</button>
            </div>
        </div>
    </div>
    

</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>