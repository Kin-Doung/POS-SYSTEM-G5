<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-10 h-10 border-radius-lg ">
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
<<<<<<< HEAD
        <div class="profile" id="profile">
=======
        <!-- Profile -->
        <div class="profile">
>>>>>>> 7a7190213c2d10e73f3045b2398271c3c62c5782
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

    <div class="input-group">
        <input type="text" id="searchInput" class="form-controlls input-group-search" placeholder="Search...">
        <select id="categorySelect" class="ms-2 selected">
            <option value="">Select Category</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option disabled>No Categories Found</option>
            <?php endif; ?>
        </select>
    </div>
    <!-- End Navbar -->
    <div class="container">
        <div class="mt-5">
            <a href="/purchase/create" class=" create-ct" style="margin-top: 30px; width : 100px;">
                <i class="bi-plus-lg"></i> Add New Cateogries
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>image</th>
                        <th>Name</th>
                        <th>price</th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchase as $index => $purchase): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $purchase['image'] ?></td>
                            <td><?= $purchase['product_name'] ?></td>
                            <td><?= $purchase['price'] ?></td>
                            <td class="text-center text-nowrap">
                                <a href="/purchase/edit?id=<?= $purchase['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#purchase<?= $purchase['id'] ?>">
                                    <i class="bi bi-trash"></i> Delete
                                </button>

                                <!-- Modal -->
                                <?php require_once './views/purchase/delete.php'; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

<<<<<<< HEAD
                                    <div class="detail-title">Order Summary</div>
                                    <table class="order-table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="details"></tbody>
                                    </table>

                                    <div class="total" id="totalPrice">Cart Total: $0.00</div>

                                    <div class="buys">
                                        <button class="buy-button" onclick="saveToPDF()">Save as PDF</button>
                                        <button class="buy-button" onclick="processPurchase()">Restock</button>
                                    </div>

                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer d-flex justify-content-between">
                                    <!-- Button to trigger restock for a specific product -->
                                    <!-- Restock button and form -->
                                    <!-- <form action="purchase/restock" method="POST">
                            <?php foreach ($purchases as $purchase): ?>
                                <div>
                                    <input type="checkbox" name="products[<?= $purchase['id'] ?>][id]" value="<?= $purchase['id'] ?>">
                                    <?= $purchase['product_name'] ?> (Current Quantity: <?= $purchase['quantity'] ?>)
                                    <input type="number" name="products[<?= $purchase['id'] ?>][quantity]" min="0" required placeholder="Restock quantity">
                                </div>
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-outline-danger fw-bold px-4">
                                <i class="fas fa-undo"></i> Restock Selected
                            </button>
                        </form> -->



                                    <button class="btn btn-outline-success fw-bold px-4" onclick="saveToPDF()">
                                        <i class="fas fa-file-pdf"></i> Save as PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <?php require_once 'views/layouts/footer.php'; ?>
</main>


<script>
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

    document.addEventListener('mouseup', function () {
        isDragging = false;
        orderSummary.style.cursor = 'default';

        setTimeout(() => {
            orderSummary.style.transition = 'top 0.2s ease, left 0.2s ease';
        }, 100);
    });
}
</script>
=======
</main>
>>>>>>> 7a7190213c2d10e73f3045b2398271c3c62c5782
