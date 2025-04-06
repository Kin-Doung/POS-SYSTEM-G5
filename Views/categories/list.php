<?php require_once './views/layouts/side.php'; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar">
        <!-- Search Bar -->
        <div class="search-container" style="background-color: #fff;">
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
        <div class="profile" id="profile">
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
    <div>
        <div class="mt-5">
            <a href="/category/create" class="create-ct">
                <i class="bi-plus-lg"></i> Add New Categories
            </a>
        </div>

        <!-- Modal to Create New Category ---------------------------------------------------->
        <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCategoryModalLabel">Create New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form to create category -->
                        <form action="/category/store" method="POST">
                            <div class="form-group">
                                <label for="categoryName" class="form-label">Category Name:</label>
                                <input type="text" name="name" id="categoryName" class="form-control" required>
                            </div>
                            <button type="submit" class="add-categories mt-3">Add category</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of create categories -------------------------------------------- -->


        <div class="table-responsive">
            <!-- Success/Failure messages -->
            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'true'): ?>
                <div class="alert alert-success">Category deleted successfully.</div>
            <?php elseif (isset($_GET['deleted']) && $_GET['deleted'] == 'false'): ?>
                <div class="alert alert-danger">Failed to delete the category.</div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-warning">Invalid request. No category ID provided.</div>
            <?php endif; ?>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Names</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $index => $category): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $category['name'] ?></td>
                            <td class="text-center">
                                <!-- Edit Icon with Tooltip -->
                                <a href="javascript:void(0);" class="icon edit-icon" data-tooltip="Edit" onclick="openEditModal(<?= $category['id'] ?>, '<?= $category['name'] ?>')">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                   </a>

                                <!-- Delete Icon with Tooltip and Confirmation -->
                                <a href="javascript:void(0);" class="icon delete-icon" data-tooltip="Delete" onclick="return confirmDelete(<?= $category['id'] ?>);">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Edit Category Modal ------------------------------------------------------>
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
                        <label for="categoryName" class="form-label">Categories Name:</label>
                        <!-- <input type="text" value=" <?= $category['name'] ?>" name="name" class="form-controll update-cate"> -->
                        <input type="text" id="categoryName<?= $category['id'] ?>" value="<?= $category['name'] ?>" name="name" class="form-control" required>

                    </div>
                    <button type="submit" class="update-categories mt-4">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end of edit categories --------------------------------------------------- -->



<script>
    // Function to open the modal and populate the form with category data
    function openEditModal(categoryId, categoryName) {
        // Set the category name in the input field
        document.getElementById('categoryName').value = categoryName;

        // Update the form's action URL to include the category ID for updating
        document.getElementById('editCategoryForm').action = '/category/update?id=' + categoryId;

        // Show the modal using Bootstrap
        var myModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        myModal.show();
    }

    // Confirmation delete function
    function confirmDelete(categoryId) {
        if (confirm('Are you sure you want to delete this category?')) {
            window.location.href = '/category/delete?id=' + categoryId;
        }
    }
</script>