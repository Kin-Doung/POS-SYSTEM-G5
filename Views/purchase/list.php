<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php'; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <nav class="navbar">
        <div class="search-container" style="background-color: #fff;">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>
        <div class="icons">
            <select id="lang" style="margin-right: 10px; padding: 5px;">
                <option value="en">English</option>
                <option value="km">Khmer</option>
            </select>
            <i class="fas fa-globe icon-btn"></i>
            <div class="icon-btn" id="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notification-count">8</span>
            </div>
        </div>
        <div class="profile">
            <img src="../../views/assets/images/image.png" alt="User">
            <div class="profile-info">
                <span id="profile-name" data-translate>Eng Ly</span>
                <span class="store-name" id="store-name" data-translate>Owner Store</span>
            </div>
        </div>
    </nav>

    <div class="container table-inventory">
        <div class="orders">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="font-weight: bold;" class="purchase-head" data-translate>Purchasing Orders</h2>
                <div>
                    <a href="/purchase/create" class="btn-new-product">
                        <i class="bi-plus-lg"></i> <span data-translate>+ Add Products</span>
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="message"><?= $_SESSION['message']; ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <table class="table" border="1">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($purchases)): ?>
                        <?php foreach ($purchases as $purchase): ?>
                            <tr>
                                <td><input type="checkbox" class="delete-checkbox" value="<?= $purchase['id']; ?>"></td>
                                <td>
                                    <!-- Display image for inventory item -->
                                    <img src="<?= htmlspecialchars($item['image']) ?>"
                                        alt="Image of <?= htmlspecialchars($item['product_name']) ?>"
                                        style="width: 40px; height:auto;">
                                </td>
                                <td><?= htmlspecialchars($purchase['product_name']); ?></td>
                                <td>
                                    <a href="/purchase/edit/<?= $purchase['id']; ?>" style="text-decoration: none; color: #007bff;">Edit</a> |
                                    <a href="/purchase/delete/<?= $purchase['id']; ?>" style="text-decoration: none; color: #dc3545;" class="delete-btn" data-id="<?= $purchase['id']; ?>" data-name="<?= htmlspecialchars($purchase['product_name']); ?>">Delete</a>
                                </td>
                               
                            </tr>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Purchase</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editForm">
                                                <input type="hidden" id="editId" name="id">
                                                <div class="mb-3">
                                                    <label for="editProductName" class="form-label">Product Name</label>
                                                    <input type="text" class="form-control" id="editProductName" name="product_name" required>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" id="saveEdit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No purchases found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>

            <!-- Delete selected button -->
            <div>
                <button id="deleteSelectedBtn" class="btn btn-danger" style="display:none;">Delete Selected</button>
            </div>
        </div>
    </div>
    <button type="button" id="bulkDeleteBtn" class="btn btn-danger pos-btn-danger" style="display: none;">Delete Selected</button>



</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Battambang&display=swap');
    .khmer-font { font-family: "Battambang", sans-serif; }

    /* Center all content in the table cell */
    td {
        padding: 6px 10px; /* Ensure there's space */
        text-align: center; /* Center content horizontally */
        vertical-align: middle; /* Center content vertically */
    }

    /* For images to be centered and have a consistent size */
    td img {
        max-width: 50px;
        height: auto;
        display: block;
        margin: 0 auto; /* Center the image */
    }

    /* For checkboxes to be centered */
    td input[type="checkbox"] {
        margin: 0 auto;
        display: block; /* Center the checkbox */
    }

    /* Center the content inside profile section */
    .profile {
        display: flex;
        align-items: center;
        justify-content: flex-end; /* Align items to the right */
    }

    /* Center the text in the profile info section */
    .profile-info {
        margin-left: 10px;
    }
</style>


<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const productName = this.dataset.productName;
            const categoryId = this.dataset.categoryId;

            document.getElementById('editId').value = id;
            document.getElementById('editProductName').value = productName;

            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });
    });

    document.getElementById('saveEdit').addEventListener('click', function() {
        const form = document.getElementById('editForm');
        const formData = new FormData(form);

        fetch('/purchase/update', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Update failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating.');
            });
    });

    // Ensure Delete buttons work (already partially handled by modal)
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            // The modal is triggered via data-bs attributes, so no additional JS needed here
        });
    });
    // Inline Editing
    document.querySelectorAll('.editable').forEach(function(element) {
        element.addEventListener('click', function() {
            const originalValue = this.textContent.trim().replace('$', '');
            const field = this.dataset.field;
            const id = this.dataset.id;

            const input = document.createElement('input');
            input.type = field === 'quantity' || field === 'price' ? 'number' : 'text';
            input.value = originalValue;
            input.className = 'form-control';
            if (field === 'quantity') input.min = '0';

            if (field === 'price') input.step = '0.01';

            this.innerHTML = '';
            this.appendChild(input);
            input.focus();

            input.addEventListener('blur', function() {
                const newValue = this.value;
                const parent = this.parentElement;
                parent.textContent = field === 'price' ? newValue + '$' : newValue;

                fetch('/purchase/update-inline', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id=${id}&field=${field}&value=${encodeURIComponent(newValue)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Update failed: ' + data.message);
                            parent.textContent = field === 'price' ? originalValue + '$' : originalValue;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        parent.textContent = field === 'price' ? originalValue + '$' : originalValue;
                    });
            });
        });
    });

    // Select all checkboxes functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.delete-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        toggleDeleteButton();
    });

    // Toggle delete button visibility
    document.querySelectorAll('.delete-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleDeleteButton);
    });

    function toggleDeleteButton() {
        const selectedCheckboxes = document.querySelectorAll('.delete-checkbox:checked');
        const deleteButton = document.getElementById('deleteSelectedBtn');
        if (selectedCheckboxes.length > 0) {
            deleteButton.style.display = 'inline-block';
        } else {
            deleteButton.style.display = 'none';
        }
    }

    // Handle delete selected action
    document.getElementById('deleteSelectedBtn').addEventListener('click', async () => {
        const selectedCheckboxes = document.querySelectorAll('.delete-checkbox:checked');
        const idsToDelete = [];
        selectedCheckboxes.forEach(checkbox => {
            idsToDelete.push(checkbox.value);
        });

        if (idsToDelete.length > 0) {
            const confirmed = await Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${idsToDelete.length} product(s)!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'No, cancel'
            });

            if (confirmed.isConfirmed) {
                const formData = new FormData();
                formData.append('ids', JSON.stringify(idsToDelete));

                // Send delete request to server
                const response = await fetch('/purchase/delete-selected', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    Swal.fire('Error', 'Failed to delete selected products.', 'error');
                }
            }
        }
    });
</script>

<?php require_once './views/layouts/footer.php'; ?>



<style>
    .purchase-head {
        color: #1a3c34;
        font-size: 24px;
        margin-bottom: 0;
    }

    .btn-new-product {
        background-color: #1a3c34;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-new-product:hover {
        background-color: #152e2a;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .table {
        background-color: white;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .table thead th {
        background-color: #1a3c34;
        color: white;
        padding: 12px;
        font-weight: 500;
        border: none;
    }

    .table td {
        padding: 12px;
        vertical-align: middle;
        border-color: #e9ecef;
    }

    .editable {
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .editable:hover {
        background-color: #f1f3f5;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 8px;
        border: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background-color: #1a3c34;
        color: white;
        border-bottom: none;
    }

    .modal-title {
        font-weight: 500;
    }

    .modal-body {
        padding: 20px;
        color: #333;
    }

    .modal-footer {
        border-top: none;
        padding: 15px 20px;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-1px);
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #c82333;
        transform: translateY(-1px);
    }

    .pos-btn-danger {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 24px;
        font-weight: 500;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* Checkbox styling */
    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    /* Image styling */
    .table td img {
        object-fit: cover;
        border: 1px solid #e9ecef;
    }
</style>