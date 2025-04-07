<nav class="navbar">
    <div class="navbar-title">Welcome to CPS</div>
    <div class="search-container">
      <i class="fas fa-search"></i>
      <input type="text" placeholder="Search">
    </div>
    <div class="icons">
      <i class="fas fa-globe icon-btn"></i>
      <div class="icon-btn" id="notification-icon">
        <i class="fas fa-bell"></i>
        <span class="notification-badge" id="notification-count">8</span>
      </div>
      <div class="cart-icon" id="cartToggle">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count" id="cartCount"></span>
        </div>
    </div>
    <div class="profile">
      <img src="../../views/assets/images/image.png " alt="User">
      <div class="profile-info">
        <span id="profile-name">Eng Ly</span>
        <span class="store-name" id="store-name">Owner Store</span>
      </div>
      <ul class="menu" id="menu">
        <li><a href="/settings" class="item">Account</a></li>
        <li><a href="/settings" class="item">Setting</a></li>
        <li><a href="/logout" class="item">Logout</a></li>
      </ul>
    </div>
  </nav>
