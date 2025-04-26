<?php
// File: views/categories/list.php
require_once './views/layouts/side.php';
?>

<div style="margin-left: 250px;">
<?php require_once './views/layouts/nav.php' ?>
</div>

<style>
    .main-content{
        margin-left: 250px;
    }
</style>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <!-- End Navbar -->

    <div>
        <!-- Main Content -->
        <div id="content">
            <div class="container">
                <div class="mt-5 d-flex justify-content-between align-items-center">
                    <a href="javascript:void(0);" class="create-ct" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        <i class="bi-plus-lg"></i> Add New Categories
                    </a>

                </div>

                <!-- Modal to Create New Category -->
                <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background: #1F51FF;">
                                <h5 class="modal-title" id="createCategoryModalLabel">Create New Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="/category/store" method="POST">
                                    <div class="form-group">
                                        <label for="createCategoryName" class="form-label">Category Name:</label>
                                        <input type="text" name="name" id="createCategoryName" class="form-control" required>
                                    </div>
                                    <button type="submit" class="add-categories mt-3 btn btn-primary">Add category</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Create Category Modal -->

                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>Category Names</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $index => $category): ?>
                                <tr>
                                <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($category['name']) ?></td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);" class="icon edit-icon" data-tooltip="Edit"
                                           onclick="openEditModal(<?= $category['id'] ?>, '<?= addslashes($category['name']) ?>')">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="icon delete-icon" data-tooltip="Delete"
                                           onclick="return confirmDelete(<?= $category['id'] ?>);">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/category/update" method="POST" id="editCategoryForm">
                        <div class="form-group">
                            <label for="editCategoryName" class="form-label">Category Name:</label>
                            <input type="text" id="editCategoryName" name="name" class="form-control" required>
                        </div>
                        <input type="hidden" id="editCategoryId" name="id">
                        <button type="submit" class="update-categories mt-4 btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Edit Category Modal -->

    <script>
        // Open edit modal
        function openEditModal(categoryId, categoryName) {
            console.log('Opening edit modal - ID:', categoryId, 'Name:', categoryName); // Debug
            document.getElementById('editCategoryName').value = categoryName;
            document.getElementById('editCategoryId').value = categoryId;
            try {
                var myModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
                myModal.show();
            } catch (e) {
                console.error('Modal error:', e);
            }
        }

        // Confirm delete
        function confirmDelete(categoryId) {
            if (confirm('Are you sure you want to delete this category?')) {
                window.location.href = '/category/delete?id=' + categoryId;
            }
        }

        // Confirm bulk delete
        function confirmBulkDelete() {
            const selectedIds = document.getElementById('selectedIds').value;
            if (selectedIds) {
                if (confirm('Are you sure you want to delete the selected categories?')) {
                    document.getElementById('bulkDeleteForm').submit();
                }
            } else {
                alert('Please select categories to delete.');
            }
        }

        // Handle checkbox logic
        const checkboxes = document.querySelectorAll('.category-checkbox');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        const selectedIdsInput = document.getElementById('selectedIds');

        function updateDeleteButtonVisibility() {
            const selected = Array.from(checkboxes).filter(cb => cb.checked);
            deleteSelectedBtn.style.display = selected.length > 0 ? 'inline-block' : 'none';
            selectedIdsInput.value = selected.map(cb => cb.value).join(',');
        }

        selectAllCheckbox.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
            updateDeleteButtonVisibility();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                updateDeleteButtonVisibility();
            });
        });
    </script>
</main>