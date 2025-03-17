<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="/" target="_blank">
            <img src="../assets/img/logos/logo.png" class="navbar-brand-img h-100" alt="main_logo">

        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white active bg-gradient-primary" href="/">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">

                <!-- //inventory page// -->
                <a class="nav-link text-white" href="inventory">


                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">table_view</i>
                    </div>
                    <span class="nav-link-text ms-1">Inventory</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="/product">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt_long</i>
                    </div>
                    <span class="nav-link-text ms-1">Billing</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="../pages/virtual-reality.html">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">view_in_ar</i>
                    </div>
                    <span class="nav-link-text ms-1">Virtual Reality</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="../pages/rtl.html">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">format_textdirection_r_to_l</i>
                    </div>
                    <span class="nav-link-text ms-1">RTL</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="../pages/notifications.html">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">notifications</i>
                    </div>
                    <span class="nav-link-text ms-1">Notifications</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="../pages/profile.html">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">person</i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="../pages/sign-in.html">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">login</i>
                    </div>
                    <span class="nav-link-text ms-1">Sign In</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="../pages/sign-up.html">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">assignment</i>
                    </div>
                    <span class="nav-link-text ms-1">Sign Up</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
        <div class="mx-3">
            <a class="btn bg-gradient-primary mt-4 w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
        </div>
    </div>
</aside>

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/now-ui-kit@1.2.0/css/now-ui-kit.min.css">

    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/now-ui-kit@1.2.0/css/now-ui-kit.min.css" rel="stylesheet">

    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-md-6 mt-3 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-globe text-warning" style="font-size: 2.5rem;"></i>

                            </div>
                            <div class="col-8 text-right">
                                <p class="card-category">Capacity</p>
                                <h4 class="card-title">150GB</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr>
                        <div class="stats text-muted">
                            <i class="fa fa-refresh"></i> Update Now
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- //end alert/ -->


    <div class="container-fluid py-4">
        <div class="row">

        </div>
        <div class="row text-center w-auto">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Inventory</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product info</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Budget</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Quantity</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Option</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2">
                                                <div>
                                                    <img src="/assets/" alt="">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">Product name</h6>
                                                    <h6 class="mb-0 mt-0 h-1 text-l fw-light">ID : 123</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$2,500</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">60</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center w-auto">
                                            <button class="btn btn-warning btn-sm">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2">
                                                <div>
                                                    <img src="../assets/img/small-logos/github.svg" class="avatar avatar-sm rounded-circle me-2" alt="invision">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 mt-0 h-1 text-sm">product name</h6>
                                                    
                                                    <h6 class="mb-0 mt-0 h-1 text-l fw-light">ID : 123</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$5,000</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">100%</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center w-auto">
                                            <button class="btn btn-warning btn-sm">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2">
                                                <div>
                                                    <img src="../assets/img/small-logos/logo-atlassian.svg" class="avatar avatar-sm rounded-circle me-2" alt="jira">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">Product name</h6>
                                                    
                                                    <h6 class="mb-0 mt-0 h-1 text-l fw-light">ID : 123</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$3,400</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">30</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-danger" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="30" style="width: 30%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center w-auto">
                                            <button class="btn btn-warning btn-sm">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2">
                                                <div>
                                                    <img src="../assets/img/small-logos/bootstrap.svg" class="avatar avatar-sm rounded-circle me-2" alt="webdev">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">product name</h6>
                            
                                                    <h6 class="mb-0 mt-0 h-1 text-l fw-light">ID : 123</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$14,000</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">80</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="80" style="width: 80%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center w-auto">
                                            <button class="btn btn-warning btn-sm">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>

                                    <tr>
                                        <td>
                                            <div class="d-flex px-2">
                                                <div>
                                                    <img src="../assets/img/small-logos/logo-slack.svg" class="avatar avatar-sm rounded-circle me-2" alt="slack">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">product name</h6>
                                                   
                                                    <h6 class="mb-0 mt-0 h-1 text-l fw-light">ID : 123</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$1,000</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">0</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0" style="width: 0%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center w-auto">
                                            <button class="btn btn-warning btn-sm">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2">
                                                <div>
                                                    <img src="../assets/img/small-logos/devto.svg" class="avatar avatar-sm rounded-circle me-2" alt="xd">
                                                </div>
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">Devto</h6>

                                                    <h6 class="mb-0 mt-0 h-1 text-l fw-light">ID : 123</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">$2,300</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">100</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center w-auto">
                                            <button class="btn btn-warning btn-sm">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
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