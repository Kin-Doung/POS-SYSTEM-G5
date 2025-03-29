<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-10 h-10 border-radius-lg ">
    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>
    <!-- Body -->
    <div class="container">
        <div class="mt-5">
            <a href="/category/create" class=" create-ct" style="margin-top: 30px; width : 100px;">
                <i class="bi-plus-lg"></i> Add New Cateogries
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $index => $category): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $category['name'] ?></td>
                            <td class="text-center text-nowrap">
                                <a href="/category/edit?id=<?= $category['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger text-white fw-bold" style="background-color: #dc3545 !important; border-color: #dc3545 !important;" data-bs-toggle="modal" data-bs-target="#category<?= $category['id'] ?>">
                                    <i class="bi bi-trash"></i> Delete
                                </button>



                                <!-- Modal -->
                                <?php require_once './views/categories/delete.php'; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

</main>