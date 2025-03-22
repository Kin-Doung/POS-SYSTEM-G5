<?php require_once './views/layouts/side.php' ?>
<?php require_once './views/layouts/header.php' ?>


<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->

    <div class="container">
        <nav class="navbar ">
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
                <img src="../../views/assets/images/image.png" alt="User">
                <div class="profile-info">
                    <span style="color: black;">Engly</span>
                    <span class="store-name">Engly store</span>
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
        <div class="container mt-4">
            <div class="row d-flex justify-content-center">
                <!-- In Stock Card -->
                <div class="col-12 col-sm-3 mb-3"> <!-- Reduced width from col-sm-4 to col-sm-3 -->
                    <div class="card shadow-sm border-success position-relative" style="height: 120px;">
                        <div class="card-body text-center d-flex flex-column justify-content-start mt-n4">
                            <i class="fas fa-box-open fa-2x text-success mb-2"></i>
                            <h5 class="card-title mb-1">In Stock</h5>
                            <p class="card-text mb-0">Items available!</p>
                        </div>
                        <div class="position-absolute top-0 end-0 p-3 text-success fs-4">
                            <strong>20</strong>
                        </div>
                    </div>
                </div>

                <!-- Out of Stock Card -->
                <div class="col-12 col-sm-3 mb-3"> <!-- Reduced width from col-sm-4 to col-sm-3 -->
                    <div class="card shadow-sm border-danger position-relative" style="height: 120px;">
                        <div class="card-body text-center d-flex flex-column justify-content-start mt-n4">
                            <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                            <h5 class="card-title mb-1">Out of Stock</h5>
                            <p class="card-text mb-0">Currently unavailable.</p>
                        </div>
                        <div class="position-absolute top-0 end-0 p-3 text-danger fs-4">
                            <strong>0</strong>
                        </div>
                    </div>
                </div>

                <!-- Full Stock Card -->
                <div class="col-12 col-sm-3 mb-3"> <!-- Reduced width from col-sm-4 to col-sm-3 -->
                    <div class="card shadow-sm border-primary position-relative" style="height: 120px;">
                        <div class="card-body text-center d-flex flex-column justify-content-start mt-n4">
                            <i class="fas fa-cogs fa-2x text-primary mb-2"></i>
                            <h5 class="card-title mb-1">Full Stock</h5>
                            <p class="card-text mb-0">Fully replenished stock!</p>
                        </div>
                        <div class="position-absolute top-0 end-0 p-3 text-primary fs-4">
                            <strong>50</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0 ms-3">Product Table</h2>
                <button type="button" class="btn-primary add-stock" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Add Stock</button>

                <!-- Modal for adding a product -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Product Display Card Container (Flexbox Layout) -->
                                <div id="product-card-container" class="d-flex flex-wrap mb-3 gap-3"></div>

                                <!-- Form for adding products -->
                                <form id="add-product-form">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="flex-fill me-2">
                                            <label for="product-name" class="col-form-label">Product Name:</label>
                                            <input type="text" class="form-control add-input" id="product-name" placeholder="Enter product name">
                                        </div>

                                        <div class="flex-fill ms-2">
                                            <label for="category" class="col-form-label">Category:</label>
                                            <select class="form-select add-input" id="category">
                                                <option selected>Choose category</option>
                                                <option value="1">Category 1</option>
                                                <option value="2">Category 2</option>
                                                <option value="3">Category 3</option>
                                            </select>
                                        </div>

                                        <div class="flex-fill me-2 ms-3">
                                            <label for="quantity" class="col-form-label">Quantity:</label>
                                            <input type="number" class="form-control add-input" id="quantity" placeholder="Enter quantity">
                                        </div>

                                        <div class="flex-fill ms-2">
                                            <label for="price" class="col-form-label">Price:</label>
                                            <input type="text" class="form-control add-input" id="price" placeholder="Enter price">
                                        </div>

                                        <!-- Image Upload -->
                                        <div class="mb-3 ms-3">
                                            <label for="product-image" class="col-form-label">Choose Image:</label>
                                            <input type="file" class="form-control add-input" id="product-image">
                                        </div>
                                    </div>

                                    <!-- Add more button -->
                                    <div>
                                        <button type="button" class="btn-primary" id="add-more-btn">Add more</button>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="text-end">
                                        <button type="submit" class="btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Table -->
            <table class="table table-striped table-bordered mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>Img</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img src="../../views/assets/images/download.png" alt="Product" class="img-fluid" style="max-width: 30px; max-height: 30px;"></td>
                        <td>Product A</td>
                        <td>10</td>
                        <td>$25.00</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn-light border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    see more...
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item text-dark" href="#">View</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">Edit</a></li>
                                    <li><a class="dropdown-item text-danger" href="#">Remove</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- JavaScript for handling Add more and localStorage -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const addMoreButton = document.getElementById("add-more-btn");
                const productCardContainer = document.getElementById("product-card-container");

                // Function to add product data and display it immediately in a card layout inside the modal
                function addProduct() {
                    const productName = document.getElementById("product-name").value;
                    const category = document.getElementById("category").value;
                    const quantity = document.getElementById("quantity").value;
                    const price = document.getElementById("price").value;
                    const productImage = document.getElementById("product-image").files[0];

                    // Check if all fields are filled
                    if (productName && category && quantity && price && productImage) {
                        // Create an object for the product
                        const newProduct = {
                            name: productName,
                            category: category,
                            quantity: quantity,
                            price: price,
                            image: URL.createObjectURL(productImage) // Convert image file to URL
                        };

                        // Store the product in localStorage
                        let productListData = JSON.parse(localStorage.getItem("productList")) || [];
                        productListData.push(newProduct);
                        localStorage.setItem("productList", JSON.stringify(productListData));

                        // Display the product immediately in a card layout inside the modal
                        displayProduct(newProduct);

                        // Clear input fields after adding
                        document.getElementById("product-name").value = "";
                        document.getElementById("category").value = "Choose category";
                        document.getElementById("quantity").value = "";
                        document.getElementById("price").value = "";
                        document.getElementById("product-image").value = "";
                    }
                }

                // Function to display the product in a card layout inside the modal (using Flexbox)
                // Function to display the product in a table layout inside the modal (using Flexbox)
function displayProduct(product) {
                    const productCard = document.createElement("div");
                    productCard.classList.add("card", "m-2", "p-3", "col-md-3", "border", "shadow-sm");

                    productCard.innerHTML = `
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col">Img</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img src="${product.image}" alt="${product.name}" class="img-fluid" style="max-height: 50px; object-fit: cover;"></td>
                        <td>${product.name}</td>
                        <td>${product.category}</td>
                        <td>${product.quantity}</td>
                        <td>$${product.price}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;

                    // Append the card to the container
                    productCardContainer.appendChild(productCard);
                }



                // Add event listener to the "Add more" button
                addMoreButton.addEventListener("click", function(e) {
                    e.preventDefault(); // Prevent form submission
                    addProduct();
                });

                // Load the products from localStorage and display them on page load (inside modal)
                const storedProducts = JSON.parse(localStorage.getItem("productList")) || [];
                storedProducts.forEach(product => displayProduct(product));
            });
        </script>


    </div>
    <footer class="footer py-4  ">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-6 mb-lg-0 mb-4">
                    <div class="copyright text-center text-sm text-muted text-lg-start">
                        © <script>
                            document.write(new Date().getFullYear())
                        </script>,
                        made by
                        <a href="#" class="font-weight-bold" target="_blank">team G5</a>

                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-muted" target="_blank">Team G5</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-muted" target="_blank">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-muted" target="_blank">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a href="#  " class="nav-link pe-0 text-muted" target="_blank">License</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    </div>
</main>

<script>

</script>