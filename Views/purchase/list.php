<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php'; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <nav class="navbar">
        <div class="search-container" style="background-color: #fff;">
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
    <div class="table-inventory">
        <div class="orders">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="font-weight: bold;" class="purchase-head">Purchasing Orders</h2>
                <div>
                    <a href="/purchase/create" class="btn-new-product">
                        <i class="bi-plus-lg"></i> + Add Products
                    </a>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Actions</th> <!-- Changed from Categories Name to Actions -->
                    </tr>
                </thead>
                <tbody id="purchasesTableBody">
                    <?php if (!empty($purchases)): ?>
                        <?php foreach ($purchases as $index => $item): ?>
                            <tr data-id="<?= htmlspecialchars($item['id']); ?>">
                                <td><input type="checkbox" class="selectItem" value="<?= htmlspecialchars($item['id']); ?>"></td>
                                <td>
                                    <img src="<?= htmlspecialchars($item['image']) ?>"
                                        alt="Image of <?= htmlspecialchars($item['product_name']) ?>"
                                        style="width: 40px; height:auto;">
                                </td>
                                <td>
                                    <span class="editable" data-field="product_name" data-id="<?= htmlspecialchars($item['id']); ?>">
                                        <?= htmlspecialchars($item['product_name']); ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- Edit Button -->
                                    <a href="/purchase/edit/<?= htmlspecialchars($item['id']); ?>"
                                        class="btn btn-primary btn-sm me-2">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <!-- Delete Button -->
                                    <button type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal<?= htmlspecialchars($item['id']); ?>">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>

                            <!-- Single Delete Modal -->
                            <div class="modal fade" id="deleteModal<?= htmlspecialchars($item['id']); ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= htmlspecialchars($item['id']); ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel<?= htmlspecialchars($item['id']); ?>">Delete Purchase</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete "<?= htmlspecialchars($item['product_name']); ?>"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form method="POST" action="/purchase/destroy/<?= htmlspecialchars($item['id']); ?>" style="display: inline;">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No purchases found.</td> <!-- Updated colspan to 4 -->
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Bulk Delete Modal -->
        <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bulkDeleteModalLabel">Delete Selected Purchases</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the selected purchases?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmBulkDelete" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="button" id="bulkDeleteBtn" class="btn btn-danger pos-btn-danger" style="display: none;">Delete Selected</button>

</main>

<!-- Existing styles remain mostly unchanged, adding some button-specific styling -->
<style>
    /* Existing styles ... */

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #c82333;
        transform: translateY(-1px);
    }
</style>
<script>
    // Existing inline editing and bulk delete scripts remain largely unchanged

    // Inline Editing (unchanged)
    document.querySelectorAll('.editable').forEach(function(element) {
        element.addEventListener('click', function() {
            const originalValue = this.textContent.trim().replace('$', '');
            const field = this.dataset.field;
            const id = this.dataset.id;

            const input = document.createElement('input');
            input.type = 'text';
            input.value = originalValue;
            input.className = 'form-control';
            this.innerHTML = '';
            this.appendChild(input);
            input.focus();

            input.addEventListener('blur', function() {
                const newValue = this.value;
                const parent = this.parentElement;
                parent.textContent = newValue;

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
                            parent.textContent = originalValue;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        parent.textContent = originalValue;
                    });
            });
        });
    });

    // Bulk Delete (unchanged)
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.selectItem').forEach(checkbox => {
            checkbox.checked = this.checked;
            toggleBulkDeleteButton();
        });
    });

    document.querySelectorAll('.selectItem').forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkDeleteButton);
    });

    function toggleBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.selectItem:checked');
        document.getElementById('bulkDeleteBtn').style.display = checkedBoxes.length > 0 ? 'inline-block' : 'none';
    }

    document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
        modal.show();
    });

    document.getElementById('confirmBulkDelete').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.selectItem:checked'))
            .map(checkbox => checkbox.value);

        fetch('/purchase/bulk-destroy', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ids: selectedIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Bulk delete failed: ' + data.message);
                }
            });
    });
</script>

<?php require_once './views/layouts/footer.php'; ?>