<nav class="navbar">
  <div class="navbar-title">Welcome to Engly Store</div>
  <div class="search-container">
    <i class="fas fa-search"></i>
    <input type="text" placeholder="Search" id="searchInput">
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
    <img src="../../views/assets/images/image.png" alt="User">
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Get the search input element and search icon
    const searchInput = document.querySelector('#searchInput');
    const searchIcon = document.querySelector('.search-container .fa-search');

    // Add event listener for real-time search as the user types
    searchInput.addEventListener('input', function() {
      filterCategories();
    });

    // Optional: Add click handler for search icon
    if (searchIcon) {
      searchIcon.addEventListener('click', filterCategories);
    }

    // Function to filter categories based on search input
    function filterCategories() {
      const searchTerm = searchInput.value.trim().toLowerCase();
      const tableRows = document.querySelectorAll('table tr'); // Assuming the categories are in a table

      // Skip the header row (first row) and loop through the data rows
      for (let i = 1; i < tableRows.length; i++) {
        const row = tableRows[i];
        const categoryNameCell = row.cells[1]; // The category name is in the second column (index 1)
        
        if (categoryNameCell) {
          const categoryName = categoryNameCell.textContent.trim().toLowerCase();

          // Show or hide the row based on whether the search term matches
          if (categoryName.includes(searchTerm)) {
            row.style.display = ''; // Show the row
          } else {
            row.style.display = 'none'; // Hide the row
          }
        }
      }

      // If the search term is empty, show all rows
      if (searchTerm === '') {
        for (let i = 1; i < tableRows.length; i++) {
          tableRows[i].style.display = '';
        }
      }
    }
  });
</script>