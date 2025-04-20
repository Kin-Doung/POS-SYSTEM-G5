<nav class="navbar">
    <div class="navbar-title" data-translate-key="Welcome to Engly Store">Welcome to Engly Store</div>
    <div class="search-container">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" data-translate-key="Search" placeholder="Search">
    </div>
    <div class="icons"> 
        <!-- Language Dropdown -->
        <div class="dropdown">
            <button class="icon-btn dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Select language" style="background-color: #fff;">
                <i class="fas fa-globe"></i>
                <span id="currentLanguage" style="margin-left: 5px;">English</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                <li><a class="dropdown-item" href="#" data-lang="en" data-translate-key="English">English</a></li>
                <li><a class="dropdown-item" href="#" data-lang="km" data-translate-key="Khmer">ខ្មែរ (Khmer)</a></li>
            </ul>
        </div>
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
            <span class="store-name" id="store-name" data-translate-key="Owner Store">Owner Store</span>
        </div>
        <ul class="menu" id="menu">
            <li><a href="/settings" class="item" data-translate-key="Account">Account</a></li>
            <li><a href="/settings" class="item" data-translate-key="Setting">Setting</a></li>
            <li><a href="/logout" class="item" data-translate-key="Logout">Logout</a></li>
        </ul>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.querySelector('#searchInput');
    const searchIcon = document.querySelector('.search-container .fa-search');

    searchInput.addEventListener('input', function() {
        filterCategories();
    });

    if (searchIcon) {
        searchIcon.addEventListener('click', filterCategories);
    }

    function filterCategories() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        const tableRows = document.querySelectorAll('table tr');

        for (let i = 1; i < tableRows.length; i++) {
            const row = tableRows[i];
            const categoryNameCell = row.cells[1];
            if (categoryNameCell) {
                const categoryName = categoryNameCell.textContent.trim().toLowerCase();
                row.style.display = categoryName.includes(searchTerm) ? '' : 'none';
            }
        }

        if (searchTerm === '') {
            for (let i = 1; i < tableRows.length; i++) {
                tableRows[i].style.display = '';
            }
        }
    }
});
</script>