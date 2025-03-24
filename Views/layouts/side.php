<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" target="_blank">
      <img src="../assets/img/logos/logo.png" class="navbar-brand-img h-100" alt="main_logo">
    <a class="navbar-brand m-0"  target="_blank">
      <img src="../../views/assets/img/logos/logo.png" class="navbar-brand-img h-100" alt="main_logo">
      <img src="../../views/assets/img/logos/logo.png" class="navbar-brand-img" alt="main_logo">
    </a>
   
  </div>
  <hr class="horizontal light mt-0 mb-2">
  <div>
    <ul class="navbar-nav">
      <!-- nav dashboard -->
      <li class="nav-item">
        <a class="nav-link text-white" href="/dashboard">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">dashboard</i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      <!-- nav inventory -->
      <li class="nav-item">
        <a class="nav-link text-white" href="/inventory">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">table_view</i>
          </div>
          <span class="nav-link-text ms-1">Inventory</span>
        </a>
      </li>
      <!-- nav product -->
      <li class="nav-item">
        <a class="nav-link text-white" href="/products">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">receipt_long</i>
          </div>
          <span class="nav-link-text ms-1">Order</span>
        </a>
      </li>
      <!-- nav Suppliers -->
      <li class="nav-item">
        <a class="nav-link text-white " href="/purchase">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">view_in_ar</i>
          </div>
          <span class="nav-link-text ms-1">Product</span>
        </a>
      </li>
      <!-- nav Categories -->
      <li class="nav-item">
        <a class="nav-link text-white " href="/category">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">format_textdirection_r_to_l</i>
          </div>
          <span class="nav-link-text ms-1">Categories</span>
        </a>
      </li>
      <!-- nav Notifications -->
      <li class="nav-item">
        <a class="nav-link text-white" href="/notifications">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">notifications</i>
          </div>
          <span class="nav-link-text ms-1">Notifications</span>
        </a>
      </li>
      <!-- nav setting -->
      <li class="nav-item mt-3">
        <a class="nav-link text-white" data-bs-toggle="collapse" href="#settingsMenu" role="button" aria-expanded="false" aria-controls="settingsMenu">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
            <i class="fas fa-cog"></i> Setting
          </h6>
        </a>
      </li>

      <div class="collapse ps-4" id="settingsMenu">
        <li class="nav-item">
          <a class="nav-link text-white" href="/settings">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person</i>
            </div>
            <span class="nav-link-text ms-1">Account</span>
          </a>
        </li>

      </div>
    </ul>
  </div>
</aside>