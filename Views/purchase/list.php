<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php'; ?>

<style>
    .main-content {
        margin-left: 250px;
    }

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
        border-radius: 10px;
        border: none;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #dc3545;
        color: white;
        border-bottom: none;
        padding: 15px 20px;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.25rem;
    }

    .modal-body {
        padding: 25px;
        color: #333;
        font-size: 1.1rem;
        line-height: 1.5;
    }

    .modal-footer {
        border-top: none;
        padding: 15px 20px;
        background-color: #f8f9fa;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 10px 25px;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
        padding: 10px 25px;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }

    .btn-danger:hover {
        background-color: #bd2130;
        transform: translateY(-2px);
        box-shadow: 0 3px 8px rgba(220, 53, 69, 0.3);
    }

    .btn-danger::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s ease, height 0.3s ease;
    }

    .btn-danger:hover::after {
        width: 200px;
        height: 200px;
    }

    .pos-btn-danger {
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 12px 30px;
        font-weight: 500;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .pos-btn-danger:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 6px 15px rgba(220, 53, 69, 0.4);
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
        border: none; /* Removed border */
    }

    .pos-btn-danger {
        background: red !important;
    }

    .delete-card {
        background: red;
    }

    .delete-card:hover {
        background: red;
    }

    .cancel-card {
        background: #0096FF;
    }

    .cancel-card:hover {
        background: #0096FF;
    }
    .purchase-head{
        font-family: "Poppins", sans-serif;
    }
</style>

<main class="main-content position-relative max-height-vh-100 h-auto">
<?php require_once './views/layouts/nav.php' ?>

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
                    <th>Categories Name</th>
                </tr>
            </thead>
            <tbody id="purchasesTableBody">
                <?php if (!empty($purchases)): ?>
                    <?php foreach ($purchases as $index => $item): ?>
                        <tr data-category-id="<?= htmlspecialchars($item['category_id']); ?>">
                            <td><input type="checkbox" class="selectItem" value="<?= htmlspecialchars($item['id']); ?>"></td>
                            <td>
                                <!-- Display image for inventory item -->
                                <img src="<?= htmlspecialchars($item['image']) ?>"
                                     alt="Image of <?= htmlspecialchars($item['product_name']) ?>"
                                     style="width: 40px; height: auto;">
                            </td>
                            <td>
                                <span class="editable" data-field="product_name" data-id="<?= htmlspecialchars($item['id']); ?>">
                                    <?= htmlspecialchars($item['product_name']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="editable" data-field="category_name" data-id="<?= htmlspecialchars($item['id']); ?>">
                                    <?= htmlspecialchars($item['category_name']); ?>
                                </span>
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
                        <td colspan="3">No purchases found.</td>
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
                    <button type="button" class="btn btn-secondary cancel-card" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmBulkDelete" class="btn btn-danger delete-card">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
<button type="button" id="bulkDeleteBtn" class="btn btn-danger pos-btn-danger" style="display: none;">Delete Selected</button>
</div>
</div>
</div>
<script src="../../views/assets/js/demo/chart-area-demo.js"></script>

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
    });
});
</script>

<?php require_once './views/layouts/footer.php'; ?>