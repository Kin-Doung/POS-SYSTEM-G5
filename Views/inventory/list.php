<?php require_once './views/layouts/side.php' ?>
<?php require_once './views/layouts/header.php' ?>


<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->

    <div class="container">
        <nav class="navbar ">
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
                    <span style="color: black;">Engly</span>
                    <span class="store-name">Engly store</span>
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
        <div class="container mt-4">
            <div class="row d-flex justify-content-center">
                <!-- In Stock Card -->
                <div class="col-12 col-sm-3 mb-3"> <!-- Reduced width from col-sm-4 to col-sm-3 -->
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
                <div class="col-12 col-sm-3 mb-3"> <!-- Reduced width from col-sm-4 to col-sm-3 -->
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
                <div class="col-12 col-sm-3 mb-3"> <!-- Reduced width from col-sm-4 to col-sm-3 -->
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
        <h2 class="mb-0 ms-3">Product Table</h2>
        <button type="button" class="btn-primary add-stock" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Add Stock</button>
            <!-- Product Table -->
            <table class="table table-striped table-bordered mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>Img</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img src="../../views/assets/images/download.png" alt="Product" class="img-fluid" style="max-width: 30px; max-height: 30px;"></td>
                        <td>Product A</td>
                        <td>10</td>
                        <td>$25.00</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn-light border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    see more...
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item text-dark" href="#"><i class="fa-solid fa-eye"></i> View</a></li>
                                    <li><a class="dropdown-item text-dark" href="#"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-trash"></i> Remove</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    <footer class="footer py-4  ">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-6 mb-lg-0 mb-4">
                    <div class="copyright text-center text-sm text-muted text-lg-start">
                        © <script>
                            document.write(new Date().getFullYear())
                        </script>,
                        made by
                        <a href="#" class="font-weight-bold" target="_blank">team G5</a>

                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-muted" target="_blank">Team G5</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-muted" target="_blank">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-muted" target="_blank">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a href="#  " class="nav-link pe-0 text-muted" target="_blank">License</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    </div>
</main>
