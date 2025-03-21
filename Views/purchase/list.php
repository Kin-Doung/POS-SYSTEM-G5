<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>
        <div class="icons">
            <i class="fas fa-globe icon-btn"></i>
            <div class="icon-btn" id="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notification-count">8</span>
            </div>
        </div>
        <div class="profile">
            <img src="../../views/assets/images/image.png" alt="User">
            <div class="profile-info">
                <span>Eng Ly</span>
                <span class="store-name">Owner Store</span>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <?php require_once 'views/layouts/header.php'; ?>
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
            <a href="/purchase/create" class="btn bg-info text-light ">Add New</a>
        </form>


        </form>

    </div>
    <!-- Modal structure -->
    <div class="container-purchase">
        <div class="product-grid" id="productGrid">
            <?php foreach ($purchases as $purchase): ?>
                <div class="card"
                    data-id="<?= htmlspecialchars($purchase['id']) ?>"
                    data-name="<?= htmlspecialchars($purchase['product_name']) ?>"
                    data-category="<?= htmlspecialchars($purchase['category_id']) ?>"
                    data-price="<?= htmlspecialchars($purchase['price']) ?>">
                    <img src="<?= '/uploads/' . htmlspecialchars($purchase['image']) ?>" alt="Product Image">

                    <div class="product-name"><?= htmlspecialchars($purchase['product_name']) ?></div>

                    <input type="number" class="quantity" id="quantity<?= $purchase['id'] ?>" value="0" min="0" />

                    <div class="price">Price: $<?= number_format($purchase['price'], 2) ?></div>

                    <div class="sum">
                        <button class="buy-button" onclick="updateCart(<?= $purchase['price'] ?>, 'quantity<?= $purchase['id'] ?>', true)">+</button>
                        <button class="subtract-button" onclick="updateCart(<?= $purchase['price'] ?>, 'quantity<?= $purchase['id'] ?>', false)">-</button>
                    </div>

                    <!-- Button to open the modal -->
                    <button type="button" class="btn btn-primary views" data-bs-toggle="modal" data-bs-target="#modal<?= $purchase['id'] ?>">
                        View detail
                    </button>
                </div>

                <!-- Modal for each product -->
                <div class="modal fade" id="modal<?= $purchase['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $purchase['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel<?= $purchase['id'] ?>">Product Detail</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form action="/purchase/update?id=<?= htmlspecialchars($purchase['id']) ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($purchase['image'] ?? '') ?>">

                                    <div class="mb-3">
                                        <label for="product_name<?= $purchase['id'] ?>" class="form-label">Name</label>
                                        <input type="text" class="formcofirm" name="product_name" id="product_name<?= $purchase['id'] ?>" value="<?= htmlspecialchars($purchase['product_name'] ?? '') ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image<?= $purchase['id'] ?>" class="form-label">Profile Image</label>
                                        <input type="file" class="formcofirm" name="image" id="image<?= $purchase['id'] ?>" accept="image/*">
                                        <?php if (!empty($purchase['image'])) : ?>
                                            <img src="/uploads/<?= htmlspecialchars($purchase['image']) ?>" alt="Current Image" style="width: 80px; height: 80px; border-radius: 50%; margin-top: 10px;">
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="price<?= $purchase['id'] ?>" class="form-label">Price</label>
                                        <input type="number" class="formcofirm" name="price" id="price<?= $purchase['id'] ?>" value="<?= htmlspecialchars($purchase['price'] ?? '') ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="category_id<?= $purchase['id'] ?>" class="form-label">Category</label>
                                        <select class="formcofirm" name="category_id" id="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php if (!empty($categories)): ?>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= htmlspecialchars($category['id']); ?>" <?= ($purchase['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="">No categories available</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>

                                <!-- Delete Form -->
                                <form action="/purchase/destroy" method="POST" style="margin-top: 10px;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($purchase['id']) ?>">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Order Summary Section -->
        <!-- Button to Open Modal -->
        <!-- Show Details Button -->
        <button class="btn btn-primary show" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">
            <i class="fas fa-eye"></i> Show Details
        </button>

        <!-- Order Details Modal -->
        <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg rounded-4">
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold"><i class="fas fa-receipt"></i> Order Summary</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body bg-light">
                        <div class="table-responsive">
                            <!-- Order Details Modal Table -->
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="details">
                                    <!-- The order items will be populated here -->

                                </tbody>
                            </table>

                        </div>
                        <div class="text-end fw-bold fs-5 mt-3">
                            <span class="text-success"><i class="fas fa-shopping-cart"></i> Cart Total:</span>
                            <span id="totalPrice">$0.00</span>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer d-flex justify-content-between">
                        <!-- Button to trigger restock for a specific product -->
                        <button class="btn btn-outline-danger fw-bold px-4" onclick="processRestock(<?= $purchase['id'] ?>)">
                            <i class="fas fa-undo"></i> Restock
                        </button>

                        <button class="btn btn-outline-success fw-bold px-4" onclick="saveToPDF()">
                            <i class="fas fa-file-pdf"></i> Save as PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- FontAwesome for Icons -->
        <!-- <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script> -->

        <!-- <script>
            function addOrderDetails(orderItems) {
                let detailsTable = document.getElementById("details");
                detailsTable.innerHTML = ''; // Clear the table before adding new rows

                let totalPrice = 0;
                orderItems.forEach(item => {
                    let row = document.createElement("tr");

                    // Create product name cell
                    let productCell = document.createElement("td");
                    productCell.innerText = item.product;
                    row.appendChild(productCell);

                    // Create quantity cell
                    let qtyCell = document.createElement("td");
                    qtyCell.classList.add("qty"); // Add the qty class
                    qtyCell.innerText = item.qty;
                    row.appendChild(qtyCell);

                    // Create price cell
                    let priceCell = document.createElement("td");
                    priceCell.innerText = `$${item.price.toFixed(2)}`;
                    row.appendChild(priceCell);

                    // Create total cell
                    let totalCell = document.createElement("td");
                    totalCell.classList.add("total"); // Add the total class
                    let total = item.qty * item.price;
                    totalCell.innerText = `$${total.toFixed(2)}`;
                    row.appendChild(totalCell);

                    detailsTable.appendChild(row);

                    totalPrice += total;
                });

                // Update the cart total
                document.getElementById("totalPrice").innerText = `$${totalPrice.toFixed(2)}`;
            }
        </script> -->

    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>