<?php
// Ensure session is started for CSRF token
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
<div style="margin-left: 250px;">
    <?php require_once './views/layouts/nav.php' ?>
</div>
<style>
    .main-content {
        margin-left: 270px;
    }

    .purchase-head {
        font-family: "Poppins", sans-serif;
    }

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

    .modal-title {
        font-family: "Poppins", sans-serif;
        font-size: 25px;
    }

    /* Increase height of View Modal */
    #viewModal<?= htmlspecialchars($item['id']) ?> .modal-content {
        min-height: 400px; /* Taller height for view modal */
    }

    /* Style for Delete Button in Delete Modal */
    #deleteModal .btn-danger {
        background: red;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        transition: all 0.3s ease;
        color: white;
    }

    #deleteModal .btn-danger:hover {
        background: darkred;
        transform: translateY(-1px);
    }

    /* Increase line height for View Modal details */
    .text-start.detail p {
        line-height: 2; /* Increased line height for better spacing */
    }

    /* Position Edit Modal closer to top */
    #editModal .modal-dialog {
        margin-top: -85px;
    }

    /* Center image in Edit Modal */
    .image-preview-container {
        display: flex;
        align-items: center;
        min-height: 100px; /* Ensure enough space for centering */
        margin-top: 10px;
    }

    #imagePreview {
        max-width: 100px;
        height: auto;
    }
</style>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

    <!-- Feedback Alerts -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

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
            <div class="input-group" style="margin-left: 0px;">
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
                        <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"></th>
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
                        $totalPrice = $item['quantity'] * $item['amount'];
                    ?>
                        <tr data-category-id="<?= htmlspecialchars($item['category_id'] ?? '') ?>" data-id="<?= htmlspecialchars($item['id']) ?>">
                            <td><input type="checkbox" class="rowCheckbox" name="selectedItems[]" value="<?= htmlspecialchars($item['id']) ?>"></td>
                            <td>
                                <img src="<?= htmlspecialchars($item['image'] ?? '/images/default.png') ?>"
                                    alt="Image of <?= htmlspecialchars($item['product_name']) ?>"
                                    style="width: 40px; height:auto;" loading="lazy">
                            </td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><span class="quantity-text"><?= htmlspecialchars($item['quantity']) ?></span></td>
                            <td>$<?= htmlspecialchars(number_format($item['amount'], 2)) ?></td>
                            <td style="display: none;">$<span class="total-price-text"><?= number_format($totalPrice, 2) ?></span></td>
                            <td style="position: absolute;">
                                <div class="dropdown">
                                    <button class="btn-seemore dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        See more...
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#viewModal<?= $item['id'] ?>"><i class="fa-solid fa-eye"></i> View</a></li>
                                        <li>
                                            <a class="dropdown-item text-primary" href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-id="<?= $item['id'] ?>"
                                                data-product_name="<?= htmlspecialchars($item['product_name']) ?>"
                                                data-category_id="<?= $item['category_id'] ?? '' ?>"
                                                data-quantity="<?= $item['quantity'] ?>"
                                                data-amount="<?= $item['amount'] ?>"
                                                data-selling_price="<?= $item['selling_price'] ?? 0 ?>"
                                                data-barcode="<?= htmlspecialchars($item['barcode'] ?? '') ?>"
                                                data-expiration_date="<?= htmlspecialchars($item['expiration_date'] ?? '') ?>"
                                                data-image="<?= htmlspecialchars($item['image'] ?? '') ?>">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                data-id="<?= $item['id'] ?>"
                                                data-product_name="<?= htmlspecialchars($item['product_name']) ?>">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal<?= $item['id'] ?>" tabindex="-1" aria-labelledby="viewModalLabel<?= $item['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content rounded-4 shadow-lg">
                                            <div class="modal-header" style="background:#add8e6; color:#000;">
                                                <h2 class="modal-title">View Inventory Item</h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body d-flex justify-content-between align-items-center">
                                                <div class="text-start detail">
                                                    <p><b>Product Name:</b> <?= htmlspecialchars($item['product_name']) ?></p>
                                                    <p><b>Category:</b> <?= !empty($item['category_name']) ? htmlspecialchars($item['category_name']) : '-' ?></p>
                                                    <p><b>Quantity:</b> <?= htmlspecialchars($item['quantity']) ?></p>
                                                    <p><b>Price:</b> $<?= htmlspecialchars(number_format($item['amount'], 2)) ?></p>
                                                    <p style="display: none;"><b>Total Price:</b> $<span class="modal-total-price"><?= htmlspecialchars(number_format($totalPrice, 2)) ?></span></p>
                                                </div>
                                                <?php if (!empty($item['image'])): ?>
                                                    <div class="mb-3">
                                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="Product Image" width="150" class="img-fluid">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="deleteModalLabel">Delete Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <strong id="deleteProductName"></strong> (ID: <span id="deleteProductId"></span>)?
                        </div>
                        <div class="modal-footer">
                            <form id="deleteForm" action="/inventory/destroy" method="POST">
                                <input type="hidden" name="id" id="deleteId">
                                <input type="hidden" name="_token" id="csrfToken" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background:#add8e6;color:#000;">
                            <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert-container" id="editAlert" style="display: none;"></div>
                            <form id="editForm" action="/inventory/update" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="_token" id="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="id" id="edit_id">
                                <div class="mb-3">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="product_name" id="product_name" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-control" name="category_id" id="category_id" required>
                                            <option value="">Select</option>
                                            <?php foreach ($categories ?? [] as $category): ?>
                                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="quantity" id="quantity" required min="0">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="amount" id="amount" required step="0.01" min="0">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Total Price</label>
                                        <div id="total_price_display" class="form-control-plaintext">$0.00</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Expiration Date</label>
                                        <input type="date" class="form-control" name="expiration_date" id="expiration_date">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" class="form-control" id="imageInput" name="image" accept="image/*">
                                    <div class="image-preview-container">
                                        <img id="imagePreview" src="" alt="Product Image" class="img-fluid" style="display: none;">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" id="updateButton">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="update-quantity" id="updateQuantitySection" style="display: none;">
                <h3>Update Quantity</h3>
                <button class="btn btn-success" onclick="updateQuantities()">Update Selected Quantities</button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Define base URL (adjust if app is in a subdirectory, e.g., '/myapp')
        const BASE_URL = window.location.origin;

        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editModal');
            // Edit Modal Population
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const productName = button.getAttribute('data-product_name');
                const categoryId = button.getAttribute('data-category_id');
                const quantity = button.getAttribute('data-quantity');
                const amount = button.getAttribute('data-amount');
                const sellingPrice = button.getAttribute('data-selling_price');
                const barcode = button.getAttribute('data-barcode');
                const expirationDate = button.getAttribute('data-expiration_date');
                const image = button.getAttribute('data-image');

                // Populate form fields
                editModal.querySelector('#edit_id').value = id;
                editModal.querySelector('#product_name').value = productName;
                editModal.querySelector('#category_id').value = categoryId || '';
                editModal.querySelector('#quantity').value = quantity;
                editModal.querySelector('#amount').value = amount;
                editModal.querySelector('#selling_price').value = sellingPrice;
                editModal.querySelector('#barcode').value = barcode;
                editModal.querySelector('#expiration_date').value = expirationDate;
                const imagePreview = editModal.querySelector('#imagePreview');
                if (image) {
                    imagePreview.src = image;
                    imagePreview.style.display = 'block';
                } else {
                    imagePreview.src = '';
                    imagePreview.style.display = 'none';
                }

                // Update total price display
                const quantityInput = editModal.querySelector('#quantity');
                const amountInput = editModal.querySelector('#amount');
                const totalPriceDisplay = editModal.querySelector('#total_price_display');

                function updateTotalPrice() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const amount = parseFloat(amountInput.value) || 0;
                    totalPriceDisplay.textContent = `$${(quantity * amount).toFixed(2)}`;
                }
                updateTotalPrice();
                quantityInput.addEventListener('input', updateTotalPrice);
                amountInput.addEventListener('input', updateTotalPrice);

                // Preview new image
                const imageInput = editModal.querySelector('#imageInput');
                imageInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });

            // Handle form submission via AJAX
            editForm.addEventListener('submit', function(event) {
                event.preventDefault();
                updateButton.disabled = true;
                updateButton.classList.add('btn-loading');

                // Client-side validation
                const productName = editForm.querySelector('#product_name').value.trim();
                const categoryId = editForm.querySelector('#category_id').value;
                const quantity = parseInt(editForm.querySelector('#quantity').value);
                const amount = parseFloat(editForm.querySelector('#amount').value);

                if (!productName) {
                    showAlert('Product name is required.', 'danger');
                    updateButton.disabled = false;
                    updateButton.classList.remove('btn-loading');
                    return;
                }
                if (!categoryId) {
                    showAlert('Please select a category.', 'danger');
                    updateButton.disabled = false;
                    updateButton.classList.remove('btn-loading');
                    return;
                }
                if (isNaN(quantity) || quantity < 0) {
                    showAlert('Quantity must be a non-negative number.', 'danger');
                    updateButton.disabled = false;
                    updateButton.classList.remove('btn-loading');
                    return;
                }
                if (isNaN(amount) || amount < 0) {
                    showAlert('Amount must be a non-negative number.', 'danger');
                    updateButton.disabled = false;
                    updateButton.classList.remove('btn-loading');
                    return;
                }

                const formData = new FormData(editForm);
                fetch(`${BASE_URL}/inventory/update`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response Status:', response.status);
                    // Log raw response for debugging
                    response.clone().text().then(raw => {
                        console.log('Raw Response:', raw);
                    });
                    if (!response.ok) {
                        return response.text().then(text => {
                            let errorData;
                            try {
                                errorData = JSON.parse(text);
                            } catch (e) {
                                throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                            }
                            throw new Error(errorData.error || `HTTP error! Status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showAlert(data.message || 'Inventory updated successfully.', 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showAlert(data.error || 'Failed to update item.', 'danger');
                        updateButton.disabled = false;
                        updateButton.classList.remove('btn-loading');
                    }
                })
                .catch(error => {
                    console.error('Error updating item:', error.message);
                    showAlert(error.message || 'An error occurred while updating. Please try again.', 'danger');
                    updateButton.disabled = false;
                    updateButton.classList.remove('btn-loading');
                });
            });

            // Delete Modal Population
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const productName = button.getAttribute('data-product_name');

                deleteModal.querySelector('#deleteProductName').textContent = productName;
                deleteModal.querySelector('#deleteProductId').textContent = id;
                deleteModal.querySelector('#deleteId').value = id;
            });

            // Filter Table
            function filterTable() {
                const categoryId = document.getElementById('categorySelect').value;
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    if (!categoryId || row.getAttribute('data-category-id') === categoryId) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Toggle Select All
            function toggleSelectAll() {
                const selectAllCheckbox = document.getElementById('selectAll');
                const checkboxes = document.querySelectorAll('.rowCheckbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                toggleUpdateQuantitySection();
            }

            // Toggle Update Quantity Section
            function toggleUpdateQuantitySection() {
                const checkboxes = document.querySelectorAll('.rowCheckbox');
                const updateSection = document.getElementById('updateQuantitySection');
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                updateSection.style.display = anyChecked ? 'block' : 'none';
            }

            // Update Quantities
            function updateQuantities() {
                const selectedItems = document.querySelectorAll('.rowCheckbox:checked');
                const data = Array.from(selectedItems).map(checkbox => ({
                    id: checkbox.value,
                    quantity: prompt(`Enter new quantity for item ID ${checkbox.value}:`)
                }));
                if (data.length > 0) {
                    console.log('Update quantities:', data);
                    // Implement AJAX call to /inventory/bulk-update if needed
                }
            }

            // Attach event listeners
            document.querySelectorAll('.rowCheckbox').forEach(checkbox => {
                checkbox.addEventListener('change', toggleUpdateQuantitySection);
            });
        });
    </script>
</main>
<?php require_once './views/layouts/footer.php' ?>