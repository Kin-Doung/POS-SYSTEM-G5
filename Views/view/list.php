<div class="content">
        <a href="/user/create" class="btn  btn-primary"> add new</a>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> User List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="container mt-3">
                                <table class="table">
                                    <thead>

                                        <tr>
                                            <th>ID</th>
                                            <th>Profile</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $index => $user): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <!-- Display image if available -->
                                                    <?php if (!empty($user['image'])): ?>
                                                        <img src="data:image/jpeg;base64,<?= base64_encode($user['image']) ?>"
                                                            alt="Profile Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 360px;">
                                                    <?php else: ?>
                                                        No image
                                                        
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $user['name'] ?></td>
                                                <td>
                                                    <a href="/user/edit?id=<?= $user['id'] ?>" class="btn btn-warning">Edit</a> |
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#user<?= $user['id'] ?>">
                                                        delete
                                                    </button>
                                                    <!-- Modal -->
                                                    <?php require 'delete.php' ?>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>