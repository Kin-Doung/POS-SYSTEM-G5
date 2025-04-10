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
                <li>
                    <a href="#" class="item qr-code-small" id="qrCodeLink" >
                        QR code 
                    </a>
                </li>
                <li><a href="/settings" class="item">Account</a></li>
                <li><a href="/settings" class="item">Setting</a></li>
                <li><a href="/logout" class="item">Logout</a></li>
            </ul>
            <!-- QR Code Card -->
            <div class="qr-card" id="qrCard">
                <img src="../../views/assets/images/QR-code.png" alt="QR Code" class="qr-code-large">
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.querySelector('#searchInput');
            const searchIcon = document.querySelector('.search-container .fa-search');
            const qrCodeLink = document.getElementById('qrCodeLink');
            const qrCard = document.getElementById('qrCard');

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
                const tableRows = document.querySelectorAll('table tr');

                for (let i = 1; i < tableRows.length; i++) {
                    const row = tableRows[i];
                    const categoryNameCell = row.cells[1];

                    if (categoryNameCell) {
                        const categoryName = categoryNameCell.textContent.trim().toLowerCase();
                        if (categoryName.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                }

                if (searchTerm === '') {
                    for (let i = 1; i < tableRows.length; i++) {
                        tableRows[i].style.display = '';
                    }
                }
            }

            // QR code card toggle
            qrCodeLink.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default link behavior
                qrCard.style.display = qrCard.style.display === 'block' ? 'none' : 'block';
            });

            // Close QR card when clicking outside
            document.addEventListener('click', function(e) {
                if (!qrCard.contains(e.target) && e.target !== qrCodeLink && !qrCodeLink.contains(e.target)) {
                    qrCard.style.display = 'none';
                }
            });
        });
    </script>
