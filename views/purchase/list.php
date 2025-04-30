<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php'; ?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" style="margin-left: 250px;">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <?php require_once './views/layouts/nav.php' ?>
                <div class="container table-inventory">
                    <div class="orders">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 style="font-weight: bold;" class="purchase-head" data-translate-key="Purchasing_Orders">Purchasing Orders</h2>
                            <div>
                                <a href="/purchase/create" class="btn-new-product" data-translate-key="Add_Products">
                                    <i class="bi-plus-lg"></i> + Add Products
                                </a>
                            </div>
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th data-translate-key="Image">Image</th>
                                    <th data-translate-key="Product_Name">Product Name</th>
                                    <th data-translate-key="Action">Action</th>
                                </tr>
                            </thead>
                            <tbody id="purchasesTableBody">
                                <?php if (!empty($purchases)): ?>
                                    <?php foreach ($purchases as $index => $item): ?>
                                        <tr data-category-id="<?= htmlspecialchars($item['category_id']); ?>">
                                            <td><input type="checkbox" class="selectItem" value="<?= htmlspecialchars($item['id']); ?>"></td>
                                            <td><img src="<?= $item['image'] ?>" alt="Product Image" width="50" loading="lazy"></td>
                                            <td>
                                                <span class="editable" data-field="product_name" data-id="<?= htmlspecialchars($item['id']); ?>">
                                                    <?= htmlspecialchars($item['product_name']); ?>
                                                </span>
                                            </td>
                                            <td class="action-column">
                                                <a href="/purchase/edit/<?= htmlspecialchars($item['id']); ?>" class="edit-btn" title="" data-translate-key="">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form method="POST" action="/purchase/destroy/<?= htmlspecialchars($item['id']); ?>" class="inline-form" onsubmit="return confirmTranslation('Confirm_Delete_Item');">
                                                    <button type="submit" class="delete-btn" title="Delete" data-translate-key="Delete_Item">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" data-translate-key="No_Purchases_Found">No purchases found.</td>
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
                                    <h5 class="modal-title" id="bulkDeleteModalLabel" data-translate-key="Delete_Selected_Product">Delete Selected Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" data-translate-key="Confirm_Bulk_Delete">
                                    Are you sure that you want to delete the selected all products?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="confirmBulkDelete" class="btn card-btn-delete btn-danger" data-translate-key="Delete">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger pos-btn-danger" style="display: none;" data-translate-key="Delete_Selected">Delete Selected</button>
            </div>
        </div>
    </div>

    <!-- Styles (unchanged, included for completeness) -->
    <style>
        .purchase-head {
            color: #1a3c34;
            font-size: 24px;
            margin-bottom: 0;
            font-family: "Poppins", sans-serif;
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
        .modal-content {
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        .modal-header {
            background-color: red;
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
        .card-btn-delete {
            background: red;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            transition: all 0.3s ease;
            color: white;
        }
        .card-btn-delete:hover {
            background: darkred;
            transform: translateY(-1px);
        }
        .pos-btn-danger {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 24px;
            font-weight: 500;
            border-radius: 6px;
            background: red;
            color: white;
        }
        .pos-btn-danger:hover {
            background: darkred;
            transform: translateY(-1px);
        }
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .table td img {
            object-fit: cover;
        }
        .edit-btn {
            background: none;
        }
        .delete-btn {
            background: none;
            color: red;
        }
        .action-column {
            text-align: center;
            white-space: nowrap;
        }
        .inline-form {
            display: inline;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            margin: 0 2px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s ease-in-out;
            color: white;
        }
    </style>

    <script src="../../views/assets/js/demo/chart-area-demo.js"></script>
    <script>
        // Function to get translated text
        function getTranslation(key) {
            const lang = localStorage.getItem('selectedLanguage') || 'en';
            return translations[lang][key] || key;
        }

        // Modified confirmation function for translations
        function confirmTranslation(key) {
            return confirm(getTranslation(key));
        }

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

        // Ensure translations are applied on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedLang = localStorage.getItem('selectedLanguage') || 'en';
            applyTranslations(savedLang);
        });
    </script>
</body>
<?php require_once './views/layouts/footer.php'; ?>