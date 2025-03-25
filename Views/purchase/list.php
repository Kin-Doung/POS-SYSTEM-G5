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

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modal-product-name"></h2>
            <img id="modal-product-image" src="" alt="" class="product-image" style="width:100px;height:auto;">
            <div>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" value="1" min="1" onchange="updateTotal()">
            </div>
            <div>Total Price: $<span id="total-price">0.80</span></div>
            <button onclick="orderProduct()">Order</button>
            <button onclick="saveAsPDF()">Save as PDF</button>
        </div>
    </div>
        <script>
            function openModal(productName, productPrice) {
                document.getElementById('modal-product-name').innerText = productName;
                document.getElementById('modal-product-image').src = '/views/assets/images/Cake mixer.png'; // Update as needed
                document.getElementById('total-price').innerText = productPrice.toFixed(2);
                document.getElementById('modal').style.display = 'block';
            }

            function closeModal() {
                document.getElementById('card').style.display = 'none';
            }

            function updateTotal() {
                const quantity = document.getElementById('quantity').value;
                const price = 0.80; // Update to get the actual price dynamically
                const total = (quantity * price).toFixed(2);
                document.getElementById('total-price').innerText = total;
            }

            function orderProduct() {
                alert('Order placed!');
                closeModal();
            }

            function saveAsPDF() {
                // Implement PDF saving functionality (you can use libraries like jsPDF)
                alert('Save as PDF functionality to be implemented.');
            }
        </script>





        <!-- Modal structure -->


        <?php require_once 'views/layouts/footer.php'; ?>
</main>
<script>
    function openModal(productName, productPrice) {
        document.getElementById('modal-product-name').innerText = productName;
        document.getElementById('modal-product-image').src = '/views/assets/images/Cake mixer.png'; // Update as needed
        document.getElementById('total-price').innerText = productPrice.toFixed(2);
        document.getElementById('modal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function updateTotal() {
        const quantity = document.getElementById('quantity').value;
        const price = 0.80; // Update to get the actual price dynamically
        const total = (quantity * price).toFixed(2);
        document.getElementById('total-price').innerText = total;
    }

    function orderProduct() {
        alert('Order placed!');
        closeModal();
    }

    function saveAsPDF() {
        // Implement PDF saving functionality (you can use libraries like jsPDF)
        alert('Save as PDF functionality to be implemented.');
    }
</script>


<!-- <script>
    // Function to toggle the visibility of the order summary card
    let isDragging = false;
    let startX, startY;

    function toggleOrderSummary() {
        const orderSummary = document.getElementById("orderSummary");

        if (orderSummary.style.display === "none" || orderSummary.style.display === "") {
            // Make the card visible
            orderSummary.style.display = "block";

            // Center the card if it's the first time opening
            if (!orderSummary.dataset.moved) {
                centerOrderSummary(orderSummary);
            }

            // Make the card draggable
            makeDraggable(orderSummary);
        } else {
            orderSummary.style.display = "none";
        }
    }

    function closeOrderSummary() {
        document.getElementById("orderSummary").style.display = "none";
    }

    // Function to center the order summary card on the screen
    function centerOrderSummary(orderSummary) {
        const screenWidth = window.innerWidth;
        const screenHeight = window.innerHeight;

        const cardWidth = orderSummary.offsetWidth;
        const cardHeight = orderSummary.offsetHeight;

        const left = (screenWidth - cardWidth) / 2;
        const top = (screenHeight - cardHeight) / 2;

        orderSummary.style.left = `${left}px`;
        orderSummary.style.top = `${top}px`;

        // Mark it as moved to prevent resetting its position every time
        orderSummary.dataset.moved = "true";
    }

    // Function to make the card draggable
    function makeDraggable(orderSummary) {
        orderSummary.addEventListener('mousedown', function(e) {
            if (e.target !== orderSummary) return;

            isDragging = true;
            startX = e.clientX - orderSummary.offsetLeft;
            startY = e.clientY - orderSummary.offsetTop;

            orderSummary.style.cursor = 'move';
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;

            const x = e.clientX - startX;
            const y = e.clientY - startY;

            orderSummary.style.transition = 'none';
            orderSummary.style.left = `${x}px`;
            orderSummary.style.top = `${y}px`;
        });

        document.addEventListener('mouseup', function() {
            isDragging = false;
            orderSummary.style.cursor = 'default';

            setTimeout(() => {
                orderSummary.style.transition = 'top 0.2s ease, left 0.2s ease';
            }, 100);
        });
    }
</script> -->