<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php'; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmBulkDelete" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="button" id="bulkDeleteBtn" class="btn btn-danger" style="display: none;">Delete Selected</button>

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