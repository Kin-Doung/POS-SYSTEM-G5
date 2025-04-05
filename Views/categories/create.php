<?php require_once './views/layouts/side.php' ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <?php require_once './views/layouts/nav.php' ?>
    <!-- End Navbar -->
    <div class="container">
        <form action="/category/store" method="POST">
            <div class="form-group">
                <label for="" class="form-label">Name:</label>
                <input type="text" value="" name="name" class="form-controll">
            </div>
            <button type="submit" class="btn btn-success mt-3">Submit</button>
        </form>
    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</main>