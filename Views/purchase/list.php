<?php
require_once './views/layouts/side.php';
?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <!-- Navbar -->
    <nav class="navbar">
        <!-- Search Bar -->
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search..." class="search-bar">
        </div>
        <div class="icons">
            <i class="fas fa-globe icon-btn"></i>
            <div class="icon-btn" id="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notification-count">8</span>
            </div>
        </div>
        <div class="profile" id="profile">
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
            <link rel="stylesheet" href="../../views/assets/css/settings/list.css">
            <script src="../../views/assets/js/setting.js"></script>
        </div>
    </nav>

    <!-- End Navbar -->

    <!-- Search Section -->
    <div class="search-section">
        <form action="" method="POST">
            <input type="text" class="search-input" id="searchInput" name="searchInput" placeholder="Search for products..." onkeyup="filterProducts()" />

            <select class="category-select" id="categorySelect" name="categorySelect" onchange="filterProducts()">
                <option value="">Select Category</option>
                <option value="all">All</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['name']) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select class="price-select" id="priceSelect" name="priceSelect" onchange="filterProducts()">
                <option value="">Select Price Range</option>
                <option value="0">Up to $10</option>
                <option value="15">Up to $15</option>
                <option value="20">Up to $20</option>
                <option value="25">Up to $25</option>
                <option value="30">Up to $30</option>
            </select>
            <!-- <a href="/purchase/create" class="btn bg-info text-light" style="margin-top: -10px;">Add New</a> -->
        </form>
    </div>

    <div class="cards">
        <div class="card">
            <div class="new-badge">20</div>
            <div class="products">
                <img src="/views/assets/images/Cake mixer.png" alt="Cake mixer" class="product-image" style="width:80%;height:auto;">
            </div>
            <div class="price">$0.80</div>
            <div class="product-name">Cake mixer</div>
            <div class="rating">⭐⭐⭐⭐⭐ (4)</div>
            <button class="buy-button" onclick="openModal('Cake mixer', 0.80)">Buy now</button>
        </div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modal-product-name"></h2>
            <img id="modal-product-image" src="" alt="Product Image" style="width:100%; height:auto;">
            <div>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" value="1" min="1" onchange="updateTotal()">
            </div>
            <div>Total Price: $<span id="total-price-usd">0.80</span></div>
            <button onclick="orderAndUpdateTotal()">Order</button>
            <button onclick="saveAsPDF()">Save as PDF</button>
        </div>
    </div>

    <script>
        function openModal(productName, productPrice, productImage) {
            document.getElementById('modal-product-name').innerText = productName;
            document.getElementById('modal-product-image').src = productImage;
            updateTotal(productPrice);
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function updateTotal(price) {
            const quantity = document.getElementById('quantity').value;
            const totalUSD = (quantity * price).toFixed(2);
            document.getElementById('total-price-usd').innerText = totalUSD;
        }

        function orderAndUpdateTotal() {
            const quantity = document.getElementById('quantity').value;
            const totalPrice = document.getElementById('total-price-usd').innerText;
            alert(`Order placed for ${quantity} items. Total Price: $${totalPrice}`);
            closeModal();
        }

        function saveAsPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            const productName = document.getElementById('modal-product-name').innerText;
            const totalUSD = document.getElementById('total-price-usd').innerText;

            doc.text(`Product: ${productName}`, 10, 10);
            doc.text(`Total Price (USD): $${totalUSD}`, 10, 20);

            doc.save('product-details.pdf');
        }
    </script>
    



    <!-- Modal structure -->




    <!-- Modal -->





    <!-- Modal structure -->


    <?php require_once 'views/layouts/footer.php'; ?>
</main>