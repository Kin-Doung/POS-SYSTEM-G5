<?php require_once './views/layouts/side.php' ?>
<?php require_once './views/layouts/header.php' ?>


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
            <img src="../../views/assets/images/image.png" alt="User">
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
                            <button class="show-button d-flex align-items-center gap-2 border-0 rounded-pill py-1 px-6" style="font-size: 0.7rem;">
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
                            <button class="show-button d-flex align-items-center gap-2 border-0 rounded-pill py-1 px-6" style="font-size: 0.7rem;">
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
                            <button class="show-button d-flex align-items-center gap-2 border-0 rounded-pill py-1 px-6" style="font-size: 0.7rem;">
                                <i class="fas fa-caret-down"></i> show
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <title>Purchasing Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <div class="container mt-4" style="width: 95%;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Purchasing Orders</h2>
            <div>
                <a href="/inventory/create" class="btn btn-secondary">
                    <i class="bi-plus-lg"></i> + New Products
                </a>

                <!-- <a href="/inventory/create">class="btn btn-primary">+ New Products</a> -->
                <button class="btn btn-secondary" id="batchActionBtn" disabled>Batch Action</button>
            </div>
        </div>

        <div class="input-group mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search...">
            <select id="categorySelect" class="form-select ms-2" onchange="filterTable()"> <!-- Added margin start -->
                <option value="">Select Category</option>
                <option value="category1">Category 1</option>
                <option value="category2">Category 2</option>
                <option value="category3">Category 3</option>
                <option value="category4">Category 4</option>
            </select>
        </div>

        <table class="table">
            <thead class=" text-primary">
                <th>
                    Image
                </th>
                <th>
                    Product Name
                </th>
                <th>
                    Quantity
                </th>
                <th>
                    Price
                </th>
                <th>
                    Action
                </th>

            </thead>
            <tbody>
                <?php foreach ($inventory as $index => $iterm): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <img src="<?= $iterm['image'] ?>" alt="User Image" style="width: 40px; height: 40px; border-radius: 100%;">
                         
                        </td>
                        <td>
                            <?= $iterm['product_name'] ?>
                         
                        </td>
                        <td>
                        <?= $iterm['Quantity'] ?>
                         
                        </td>
                        <td>
                            <<?= $iterm['amount'] ?>
                         
                        </td>
                        <td>
                            <a href="/inventory/edit?id=<?= $iterm['id'] ?>" class="btn btn-warning">Edit</a> |
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#user<?= $iterm['id'] ?>">
                                delete
                            </button>

                            <!-- Modal -->
                            <?php require 'delete.php' ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>


        <div class="update-quantity" id="updateQuantitySection" style="display: none;">
            <h3>Update Quantity</h3>
            <button class="btn btn-success" onclick="updateQuantities()">Update Selected Quantities</button>
        </div>
    </div>

    </div>
</main>