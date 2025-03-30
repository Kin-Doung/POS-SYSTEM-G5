<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php'; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

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
                <span id="profile-name">Eng Ly</span>
                <span class="store-name" id="store-name">Owner Store</span>
            </div>
            <!-- <ul class="menu" id="menu">
                <li><a href="/settings" class="item">Account</a></li>
                <li><a href="/settings" class="item">Setting</a></li>
                <li><a href="/logout" class="item">Logout</a></li>
            </ul> -->
        </div>
    </nav>

    <div class="container table-inventory">
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
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Type of Products</th>
                    </tr>
                </thead>
                <tbody id="purchasesTableBody">
                    <?php if (!empty($purchases)): ?>
                        <?php foreach ($purchases as $index => $item): ?>
                            <tr data-category-id="<?= htmlspecialchars($item['category_id']); ?>">

                                <td><input type="checkbox" class="selectItem" value="<?= htmlspecialchars($item['id']); ?>"></td>
                                <td>
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="data:image/jpeg;base64,<?= base64_encode($item['image']); ?>"
                                            alt="Image of <?= htmlspecialchars($item['product_name']); ?>"
                                            style="width: 40px; height: 40px; border-radius: 100%;">
                                    <?php else: ?>
                                        <span>No image</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="editable" data-field="product_name" data-id="<?= htmlspecialchars($item['id']); ?>">
                                        <?= htmlspecialchars($item['product_name']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="editable" data-field="quantity" data-id="<?= htmlspecialchars($item['id']); ?>">
                                        <?= htmlspecialchars($item['quantity']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="editable" data-field="price" data-id="<?= htmlspecialchars($item['id']); ?>">
                                        <?= htmlspecialchars($item['price']); ?>$
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($item['type_of_product']); ?></td>
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
                            <td colspan="8">No purchases found.</td>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel harrowing</button>
                        <button type="button" id="confirmBulkDelete" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="button" id="bulkDeleteBtn" class="btn btn-danger pos-btn-danger" style="display: none;">Delete Selected</button>

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

</main>

<script>
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
            if (field === 'quantity') input.min = '1';
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

    // Bulk Delete
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
            })

    });
</script>

<?php require_once './views/layouts/footer.php'; ?>