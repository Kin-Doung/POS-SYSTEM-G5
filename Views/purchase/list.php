<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-10 h-10 border-radius-lg ">
    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>

    <div class="input-group">
        <input type="text" id="searchInput" class="form-controlls input-group-search" placeholder="Search...">
        <select id="categorySelect" class="ms-2 selected">
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
    <!-- End Navbar -->
    <div class="container">
        <div class="mt-5">
            <a href="/purchase/create" class=" create-ct" style="margin-top: 30px; width : 100px;">
                <i class="bi-plus-lg"></i> Add New Cateogries
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>image</th>
                        <th>Name</th>
                        <th>price</th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchase as $index => $purchase): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $purchase['image'] ?></td>
                            <td><?= $purchase['product_name'] ?></td>
                            <td><?= $purchase['price'] ?></td>
                            <td class="text-center text-nowrap">
                                <a href="/purchase/edit?id=<?= $purchase['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#purchase<?= $purchase['id'] ?>">
                                    <i class="bi bi-trash"></i> Delete
                                </button>

                                <!-- Modal -->
                                <?php require_once './views/purchase/delete.php'; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

</main>