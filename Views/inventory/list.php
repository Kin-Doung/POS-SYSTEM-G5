<?php require_once './views/layouts/header.php' ?>
<?php require_once './views/layouts/side.php' ?>

<style>
    .main-content {
        margin-left: 270px;
    }

    .purchase-head {
        font-family: "Poppins", sans-serif;
    }
</style>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>

    <!-- End Navbar -->

    <div class="table-inventory me-4">
        <div class="orders">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="font-weight: bold;" class="purchase-head">Restock Products</h2>
                <div>
                    <a href="/inventory/create" class="btn-new-product">
                        <i class="bi-plus-lg"></i> + Add Stocks
                    </a>
                </div>
            </div>
            <div class="input-group">
                <select id="categorySelect" class="ms-2 selected" onchange="filterTable()">
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
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"></th> <!-- Checkbox column -->
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th style="display: none;">Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory as $index => $item):
                        $totalPrice = $item['quantity'] * $item['amount']; // Calculate total price for each item
                    ?>
                        <tr data-category-id="<?= htmlspecialchars($item['category_id']); ?>">
                            <td><input type="checkbox" class="rowCheckbox" name="selectedItems[]" value="<?= htmlspecialchars($item['id']); ?>"></td>
                            <td>
                                <img src="<?= htmlspecialchars($item['image']) ?>"
                                    alt="Image of <?= htmlspecialchars($item['product_name']) ?>"
                                    style="width: 40px; height:auto;">
                            </td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><span class="quantity-text"><?= htmlspecialchars($item['quantity']) ?></span></td>
                            <td>$<?= htmlspecialchars(number_format($item['amount'], 2)) ?></td>
                            <td style="display: none;">$<span class="total-price-text"><?= number_format($totalPrice, 2) ?></span></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn-seemore dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        See more...
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#viewModal<?= $item['id']; ?>"><i class="fa-solid fa-eye"></i> View</a></li>
                                        <li>
                                            <a class="dropdown-item text-dark" href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-id="<?= $item['id'] ?>"
                                                data-product_name="<?= htmlspecialchars($item['product_name']) ?>"
                                                data-category_id="<?= $item['category_id'] ?>"
                                                data-quantity="<?= $item['quantity'] ?>"
                                                data-amount="<?= $item['amount'] ?>"
                                                data-total_price="<?= $totalPrice ?>"
                                                data-image="<?= htmlspecialchars($item['image']) ?>">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </a>
                                        </li>
                                        <li><a class="dropdown-item text-dark" href="/inventory/delete?id=<?= $item['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa-solid fa-trash"></i> Delete</a></li>
                                    </ul>
                                </div>
                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal<?= $item['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content rounded-4 shadow-lg">
                                            <div class="modal-header" style="background: #1F51FF;">
                                                <h2 class="modal-title">View Inventory Item</h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body d-flex justify-content-between align-items-center">
                                                <div class="text-start detail">
                                                    <p><strong>Product Name:</strong> <?= htmlspecialchars($item['product_name']); ?></p>
                                                    <p><strong>Category:</strong> <?= !empty($item['category_name']) ? htmlspecialchars($item['category_name']) : '-'; ?></p>
                                                    <p><strong>Quantity:</strong> <?= htmlspecialchars($item['quantity']); ?></p>
                                                    <p><strong>Price:</strong> $<?= htmlspecialchars(number_format($item['amount'], 2)); ?></p>
                                                    <p style="display: none;"><strong>Total Price:</strong> $<span class="modal-total-price"><?= htmlspecialchars(number_format($totalPrice, 2)); ?></span></p>
                                                </div>
                                                <?php if (!empty($item['image'])): ?>
                                                    <div class="mb-3">
                                                        <img src="<?= htmlspecialchars($item['image']); ?>" alt="Product Image" width="150" class="img-fluid">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background: #1F51FF;">
                                                <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/inventory/update?id=" method="POST" enctype="multipart/form-data">
                                                    <div class="mb-3">
                                                        <label class="form-label">Product Name</label>
                                                        <input type="text" class="form-control" name="product_name" id="product_name" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Category</label>
                                                            <select class="form-control" name="category_id" id="category_id" required>
                                                                <option value="">Select</option>
                                                                <?php foreach ($categories ?? [] as $category): ?>
                                                                    <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Quantity</label>
                                                            <input type="number" class="form-control" name="quantity" id="quantity" required min="1">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Amount</label>
                                                            <input type="number" class="form-control" name="amount" id="amount" required step="0.01" min="0">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Total Price</label>
                                                            <input type="text" class="form-control" name="total_price" id="total_price" readonly>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Expiration Date</label>
                                                            <input type="date" class="form-control" name="expiration_date" id="expiration_date">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Image</label>
                                                        <input type="file" class="form-control" id="imageInput" name="image" accept="image/*">
                                                        <img id="imagePreview" src="" alt="Product Image" width="50" height="50" class="rounded-circle mt-2">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Updated JavaScript -->
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Existing modal population code
                    document.querySelectorAll('.dropdown-item[data-bs-target="#editModal"]').forEach((link) => {
                        link.addEventListener("click", function(event) {
                            const productName = event.target.getAttribute("data-product_name");
                            const categoryId = event.target.getAttribute("data-category_id");
                            const quantity = event.target.getAttribute("data-quantity");
                            const amount = event.target.getAttribute("data-amount");
                            const totalPrice = event.target.getAttribute("data-total_price");
                            const image = event.target.getAttribute("data-image");
                            const id = event.target.getAttribute("data-id");

                            document.getElementById("product_name").value = productName;
                            document.getElementById("category_id").value = categoryId;
                            document.getElementById("quantity").value = quantity;
                            document.getElementById("amount").value = amount;
                            document.getElementById("total_price").value = parseFloat(totalPrice).toFixed(2);
                            document.getElementById("imagePreview").src = image ? image : "";
                            document.querySelector("#editModal form").action = "/inventory/update?id=" + id;
                        });
                    });

                    // Add total price calculation in edit modal
                    const quantityInput = document.getElementById("quantity");
                    const amountInput = document.getElementById("amount");
                    const totalPriceInput = document.getElementById("total_price");

                    function updateTotalPrice() {
                        const quantity = parseFloat(quantityInput.value) || 0;
                        const amount = parseFloat(amountInput.value) || 0;
                        const total = quantity * amount;
                        totalPriceInput.value = total.toFixed(2);
                    }

                    if (quantityInput && amountInput) {
                        quantityInput.addEventListener("input", updateTotalPrice);
                        amountInput.addEventListener("input", updateTotalPrice);
                    }

                    // Add grand total display
                    function updateGrandTotal() {
                        let grandTotal = 0;
                        document.querySelectorAll('tbody tr').forEach(row => {
                            if (row.style.display !== 'none') {
                                const totalPrice = parseFloat(row.querySelector('.total-price-text').textContent.replace(/,/g, ''));
                                grandTotal += totalPrice;
                            }
                        });

                        let grandTotalDiv = document.getElementById('grandTotal');
                        if (!grandTotalDiv) {
                            grandTotalDiv = document.createElement('div');
                            grandTotalDiv.id = 'grandTotal';
                            grandTotalDiv.className = 'grand-total-container';
                            document.querySelector('.table').after(grandTotalDiv);
                        }
                        grandTotalDiv.innerHTML = `Grand Total: $${grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
                    }

                    // Update existing filterTable function to include grand total
                    function filterTable() {
                        const searchInput = document.getElementById("searchInput");
                        const categorySelect = document.getElementById("categorySelect");
                        const tableRows = document.querySelectorAll("tbody tr");

                        const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
                        const selectedCategory = categorySelect.value;

                        tableRows.forEach((row) => {
                            const productName = row.children[2].textContent.toLowerCase();
                            const categoryId = row.getAttribute("data-category-id");

                            const matchesSearch = searchInput ? productName.includes(searchValue) : true;
                            const matchesCategory = selectedCategory === "" || categoryId === selectedCategory;

                            row.style.display = matchesSearch && matchesCategory ? "" : "none";
                        });

                        updateGrandTotal();
                        updateCheckboxStateAfterFilter();
                    }

                    // Add event listeners if search elements exist
                    const searchInput = document.getElementById("searchInput");
                    const categorySelect = document.getElementById("categorySelect");
                    if (searchInput) searchInput.addEventListener("input", filterTable);
                    if (categorySelect) categorySelect.addEventListener("change", filterTable);

                    // Initial grand total calculation
                    updateGrandTotal();

                    // Checkbox functionality: Select/Deselect all
                    function toggleSelectAll() {
                        const selectAllCheckbox = document.getElementById("selectAll");
                        const rowCheckboxes = document.querySelectorAll(".rowCheckbox");
                        rowCheckboxes.forEach(checkbox => {
                            // Only toggle checkboxes in visible rows
                            const row = checkbox.closest('tr');
                            if (row.style.display !== 'none') {
                                checkbox.checked = selectAllCheckbox.checked;
                            }
                        });
                        toggleUpdateQuantitySection();
                    }

                    // Show/hide the "Update Quantity" section based on selected checkboxes
                    function toggleUpdateQuantitySection() {
                        const rowCheckboxes = document.querySelectorAll(".rowCheckbox");
                        const updateQuantitySection = document.getElementById("updateQuantitySection");
                        const anyChecked = Array.from(rowCheckboxes).some(checkbox => {
                            const row = checkbox.closest('tr');
                            return checkbox.checked && row.style.display !== 'none';
                        });
                        updateQuantitySection.style.display = anyChecked ? "block" : "none";
                    }

                    // Add event listeners to row checkboxes
                    document.querySelectorAll(".rowCheckbox").forEach(checkbox => {
                        checkbox.addEventListener("change", function() {
                            const row = checkbox.closest('tr');
                            if (row.style.display !== 'none') {
                                toggleUpdateQuantitySection();
                                // Update "Select All" checkbox state
                                const selectAllCheckbox = document.getElementById("selectAll");
                                const rowCheckboxes = document.querySelectorAll(".rowCheckbox");
                                const visibleCheckboxes = Array.from(rowCheckboxes).filter(cb => cb.closest('tr').style.display !== 'none');
                                selectAllCheckbox.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
                            }
                        });
                    });

                    // Adjust checkbox state when filtering
                    function updateCheckboxStateAfterFilter() {
                        const rowCheckboxes = document.querySelectorAll(".rowCheckbox");
                        const selectAllCheckbox = document.getElementById("selectAll");
                        const visibleCheckboxes = Array.from(rowCheckboxes).filter(cb => cb.closest('tr').style.display !== 'none');
                        selectAllCheckbox.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
                        toggleUpdateQuantitySection();
                    }

                    // Function to handle updating quantities (placeholder for your backend logic)
                    function updateQuantities() {
                        const selectedItems = [];
                        document.querySelectorAll(".rowCheckbox:checked").forEach(checkbox => {
                            const row = checkbox.closest('tr');
                            if (row.style.display !== 'none') {
                                selectedItems.push(checkbox.value);
                            }
                        });

                        if (selectedItems.length === 0) {
                            alert("Please select at least one item to update.");
                            return;
                        }

                        // Placeholder for updating quantities - you can replace this with your actual logic
                        console.log("Selected item IDs for update:", selectedItems);
                        alert("Update functionality to be implemented. Selected items: " + selectedItems.join(", "));
                    }
                });
            </script>

            <!-- Updated CSS -->
            <style>
                .grand-total-container {
                    margin-top: 15px;
                    padding: 10px;
                    background-color: #f8f9fa;
                    border-radius: 4px;
                    text-align: right;
                    font-weight: bold;
                    font-size: 1.1em;
                }

                .total-price-text {
                    color: #2c3e50;
                }

                .modal-total-price {
                    color: #28a745;
                    font-weight: bold;
                }

                .update-quantity {
                    margin-top: 20px;
                    padding: 10px;
                    background-color: #f1f1f1;
                    border-radius: 4px;
                }
            </style>

            <div class="update-quantity" id="updateQuantitySection" style="display: none;">
                <h3>Update Quantity</h3>
                <button class="btn btn-success" onclick="updateQuantities()">Update Selected Quantities</button>
            </div>
        </div>
    </div>

    <!-- JavaScript for Edit Modal Population -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</main>