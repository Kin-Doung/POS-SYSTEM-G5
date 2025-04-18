<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 my-0 fixed-start bg-white" id="sidenav-main">
    <div class="sidenav-header my-0">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-label="Close sidebar" id="iconSidenav"></i>
        <a class="navbar-brand m-0 p-0" href="/dashboard">
            <span class="sr-only">Engly POS Home</span>
            <img src="./views/assets/img/logos/Engly-logo.png" alt="Engly POS Logo" class="img-fluid" style="max-height: 80px; margin-left: 60px">
        </a>
    </div>
    <div>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-dark" href="/dashboard">
                    <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-tachometer-alt opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/purchase">
                    <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-shopping-cart opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/inventory">
                    <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-warehouse opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Inventory</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/products">
                    <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-tag opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Order</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/history">
                    <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-history opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Report</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/profit_loss">
                    <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-balance-scale opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profit_Loss</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/tracking">
                    <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-chart-line opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Stock tracking</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/category">
                    <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-list opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Categories</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/notifications">
                    <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-bell opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Notifications</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/settings">
                    <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-cog opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Account</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/logout">
                    <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-sign-out-alt opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Log Out</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 text-center p-3">
        <small>Engly POS v1.0 © <?= date('Y') ?></small>
    </div>
    <link rel="stylesheet" href="/assets/css/settings/aside.css">
</aside>