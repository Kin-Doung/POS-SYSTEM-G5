<?php require_once './views/layouts/side.php'?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
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

        <!-- Profile -->
        <div class="profile">
            <img src="../../assets/images/image.png" alt="User">
            <div class="profile-info">
                <span>Jimmy Sullivan</span>
                <span class="store-name">Odama Store</span>
            </div>
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
    <!-- End Navbar -->
    <?php require_once 'views/layouts/header.php'; ?>
    <div class="search-section">
        <form action="" method="POST">
            <input type="text" class="search-input" id="searchInput" name="searchInput" placeholder="Search for products..." onkeyup="filterProducts()" />
            <select class="category-select" id="categorySelect" name="categorySelect" onchange="filterProducts()">
                <option value="">Select Category</option>
                <option value="all">All</option>
                <option value="kitchen">Kitchen</option>
                <option value="appliances">Appliances</option>
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
    </div>
    <!-- Modal structure -->
    <div class="container-purchase">
        <div class="product-grid" id="productGrid">
            <?php foreach ($purchases as $purchase): ?>

                <div class="card" data-name="<?= $purchase['product_name'] ?>" data-category="<?= $purchase['product_name'] ?>" data-price="<?= $purchase['price'] ?>">
                    <img src="<?= '/uploads/' . $purchase['image']; ?>" alt="Product Image">

                    <div class="product-name"><?= $purchase['product_name'] ?></div>
                    <input type="number" class="quantity" id="quantity<?= $purchase['product_name'] ?>" value="0" min="0" />
                    <div class="price">Price: $<?= number_format($purchase['price'], 2) ?></div>
                    <div class="sum">

                        <button class="buy-button" onclick="updateCart(<?= $purchase['price'] ?>, 'quantity<?= $purchase['product_name'] ?>', true)">+</button>
                        <button class="subtract-button" onclick="updateCart(<?= $purchase['price'] ?>, 'quantity<?= $purchase['product_name'] ?>', false)">-</button>
                    </div>
  
        
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">View detail</button>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Product Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        
      </div>
      <div class="modal-body">
         <div class="delete_edit">
                        <form action="/purchase/update?id=<?= isset($purchase['id']) ? htmlspecialchars($purchase['id']) : '' ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="existing_image" value="<?= htmlspecialchars($purchase['image'] ?? '') ?>">

        <div class="mb-3">
            <label for="product_name" class="form-label">Name</label>
            <input type="text" class="form-control" name="product_name" id="product_name" value="<?= htmlspecialchars($purchase['product_name'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Profile Image</label>
            <input type="file" class="form-control" name="image" id="image" accept="image/*">
            <?php if (!empty($purchase['image'])) : ?>
                <img src="/uploads/<?= htmlspecialchars($purchase['image']) ?>" alt="Current Image" style="width: 80px; height: 80px; border-radius: 50%; margin-top: 10px;">
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" name="price" id="price" value="<?= htmlspecialchars($purchase['price'] ?? '') ?>" required>
        </div>

        <!-- <button type="submit" class="btn btn-primary  btn-update">Update</button> -->
        <button type="submit" 
    style="padding: 10px 14px; 
           background-color: darkblue !important; 
           width: 50px; 
           min-width: 350px; 
           border: none; 
           color: white; 
           font-weight: bold; 
           border-radius: 8px; 
           cursor: pointer; 
           transition: all 0.3s ease;" 
    onmouseover="this.style.backgroundColor=' rgb(0, 216, 29) ';" 
    onmouseout="this.style.backgroundColor='darkblue';">
    Update
</button>

    </form>
              
     </div>
      </div>
      <div class="modal-footer">
      <form action="/purchase/destroy" method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?= $purchase['id'] ?>">
            <button type="submit"  style="background: red; max-width:100px;" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this product?');">delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
    </div>
            <?php endforeach; ?>
        </div>

        <div class="detail-section">
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
            <canvas id="qrCode" style="margin-top: 20px;"></canvas>
            <div class="buys">
                <button class="buy-button" style="margin-top: 20px; width: 50%;" onclick="saveToPDF()">Save as PDF</button>
                <button class="buy-button" style="margin-top: 15px; width: 50%; background-color: #27ae60;" onclick="processPurchase()">Restock</button>
            </div>
        </div>
    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>
