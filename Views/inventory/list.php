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

   <!-- //end alert function// -->

   
        <footer class="footer py-4  ">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <script>
                                document.write(new Date().getFullYear())
                            </script>,
                            made with <i class="fa fa-heart"></i> by
                            <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                            for a better web.
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</main>