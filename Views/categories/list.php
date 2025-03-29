<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-10 h-10 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar">
        <!-- Search Bar -->
        <div class="search-container">
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
        <div class="profile">
            <img src="../../views/assets/images/image.png" alt="User">
            <div class="profile-info">
                <span>Eng Ly</span>
                <span class="store-name">Owner Store</span>
            </div>
        </div>
        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
            </a>
        </li>
    </nav>
    <!-- End Navbar -->
    <div class="container">
        <div class="mt-5">
            <a href="/category/create" class=" create-ct" style="margin-top: 30px; width : 100px;">
                <i class="bi-plus-lg"></i> Add New Categories
            </a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
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
    <td class="text-center">
        <!-- Edit Icon with Tooltip -->
        <a href="/category/edit?id=<?= $category['id'] ?>" class="icon edit-icon" data-tooltip="Edit">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
        <!-- Delete Icon with Tooltip and SweetAlert -->
        <a href="javascript:void(0);" class="icon delete-icon" data-bs-toggle="modal" data-bs-target="#category<?= $category['id'] ?>" data-tooltip="Delete" onclick="confirmDelete(event, <?= $category['id'] ?>)">
            <i class="fa-solid fa-trash"></i>
        </a>

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

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(event, categoryId) {
    // Prevent the default action
    event.preventDefault();

    // Show SweetAlert confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, proceed with deletion (redirect or API request)
            window.location.href = '/category/delete?id=' + categoryId; // Adjust URL accordingly
        }
    });
}

</script>
<style>
   /* Style for the icons */
.icon {
    font-size: 20px; /* Adjust the size of the icons */
    color: #000; /* Default color */
    margin-right: 10px; /* Space between icons */
    cursor: pointer;
    position: relative; /* To position the tooltip */
}

/* Edit Icon style */
.edit-icon {
    color: blue !important;
}

.edit-icon:hover {
    color: #ffeb3b;
}

/* Delete Icon style */
.delete-icon {
    color: red;
}

.delete-icon:hover {
    color: #ffCDD2;
}

/* Tooltip text */
.icon[data-tooltip]:hover::after {
    content: attr(data-tooltip); /* Get the text from the data-tooltip attribute */
    position: absolute;
    top: -20px; /* Position above the icon */
    left: 50%;
    transform: translateX(-50%);
    background-color: #333; /* Dark background */
    color: #fff; /* White text */
    padding: 3px 5px;
    border-radius: 5px;
    font-size: 10px;
    white-space: nowrap;
    z-index: 10;
}

</style>