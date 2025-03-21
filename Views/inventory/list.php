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
            <img src="../../assets/images/image.png" alt="User">
            <div class="profile-info">
                <span>Engly</span>
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
                                <p class="card-category text-muted" style="font-size: 0.85rem;">Out Stock</p>
                                <h4 class="card-title" style="font-size: 1.2rem;">120</h4>
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
                                <p class="card-category text-muted" style="font-size: 0.85rem;">Low Stock</p>
                                <h4 class="card-title" style="font-size: 1.2rem;">120</h4>
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
                                <p class="card-category text-muted" style="font-size: 0.85rem;">Full Stock</p>
                                <h4 class="card-title" style="font-size: 1.2rem;">120</h4>
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

        <div class="row text-center w-auto">
            <div class="col-12">
                <div class="card my-4 m-4">
                    <div class="card-body px-0 pb-2"> Inventory
                        <div class="table-responsive p-0 " style="overflow: hidden;">
                            <table class="table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product info</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Price</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Quantity</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Option</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center px-2 flex-wrap">
                                                <div class="imageinventory me-3">
                                                    <img src="../../assets/images/Electric cooking pot.png" alt="Product Image" class="inventory-img">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">Product Name</h6>
                                                    <h6 class="mb-0 mt-1 text-muted small">ID : 123</h6>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$25.00</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">0</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center w-auto">
                                        <div class="dropdown">
                                                <button class="btn btn-sm text-lowercase border-0 shadow-none" 
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false" 
                                                     style="background: none; box-shadow: none; border: none; color:blue;">
                                                            see more...
                                                </button>
                                                    <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item text-lowercase" href="#">edit</a></li>
                                                            <li><a class="dropdown-item text-danger text-lowercase" href="#">delete</a></li>
                                                    </ul>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center px-2 flex-wrap">
                                                <div class="imageinventory me-3">
                                                    <img src="../../assets/images/Cake mixer.png" alt="Product Image" class="inventory-img">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">Product Name</h6>
                                                    <h6 class="mb-0 mt-1 text-muted small">ID : 123</h6>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$25.00</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">60</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>


                                        <td class="text-center w-auto">
                                        <!-- <div class="dropdown">
                                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    see...
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                </ul>
                                            </div> -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm text-lowercase border-0 shadow-none" 
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false" 
                                                     style="background: none; box-shadow: none; border: none; color:blue;">
                                                            see more...
                                                </button>
                                                    <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item text-lowercase" href="#">edit</a></li>
                                                            <li><a class="dropdown-item text-danger text-lowercase" href="#">delete</a></li>
                                                    </ul>
                                            </div>

                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center px-2 flex-wrap">
                                                <div class="imageinventory me-3">
                                                    <img src="../../assets/images/Cocktail machine.png" alt="Product Image" class="inventory-img">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">Product Name</h6>
                                                    <h6 class="mb-0 mt-1 text-muted small">ID : 123</h6>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$25.00</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">5</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center w-auto">
                                        <div class="dropdown">
                                                <button class="btn btn-sm text-lowercase border-0 shadow-none" 
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false" 
                                                     style="background: none; box-shadow: none; border: none; color:blue;">
                                                            see more...
                                                </button>
                                                    <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item text-lowercase" href="#">edit</a></li>
                                                            <li><a class="dropdown-item text-danger text-lowercase" href="#">delete</a></li>
                                                    </ul>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center px-2 flex-wrap">
                                                <div class="imageinventory me-3">
                                                    <img src="../../assets/images/Fan.png" alt="Product Image" class="inventory-img">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">Product Name</h6>
                                                    <h6 class="mb-0 mt-1 text-muted small">ID : 123</h6>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$25.00</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">30</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center w-auto">
                                        <div class="dropdown">
                                                <button class="btn btn-sm text-lowercase border-0 shadow-none" 
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false" 
                                                     style="background: none; box-shadow: none; border: none; color:blue;">
                                                            see more...
                                                </button>
                                                    <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item text-lowercase" href="#">edit</a></li>
                                                            <li><a class="dropdown-item text-danger text-lowercase" href="#">delete</a></li>
                                                    </ul>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center px-2 flex-wrap">
                                                <div class="imageinventory me-3">
                                                    <img src="../../assets/images/electric grill.png" alt="Product Image" class="inventory-img">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">Product Name</h6>
                                                    <h6 class="mb-0 mt-1 text-muted small">ID : 123</h6>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$25.00</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">60</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center w-auto">
                                        <div class="dropdown">
                                                <button class="btn btn-sm text-lowercase border-0 shadow-none" 
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false" 
                                                     style="background: none; box-shadow: none; border: none; color:blue;">
                                                            see more...
                                                </button>
                                                    <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item text-lowercase" href="#">edit</a></li>
                                                            <li><a class="dropdown-item text-danger text-lowercase" href="#">delete</a></li>
                                                    </ul>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center px-2 flex-wrap">
                                                <div class="imageinventory me-3">
                                                    <img src="../../assets/images/download.png" alt="Product Image" class="inventory-img">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">Product Name</h6>
                                                    <h6 class="mb-0 mt-1 text-muted small">ID : 123</h6>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$25.00</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">60</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center w-auto">
                                        <div class="dropdown">
                                                <button class="btn btn-sm text-lowercase border-0 shadow-none" 
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false" 
                                                     style="background: none; box-shadow: none; border: none; color:blue;">
                                                            see more...
                                                </button>
                                                    <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item text-lowercase" href="#">edit</a></li>
                                                            <li><a class="dropdown-item text-danger text-lowercase" href="#">delete</a></li>
                                                    </ul>
                                            </div>
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
                            <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">team G5</a>
                            
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Team G5</a>
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