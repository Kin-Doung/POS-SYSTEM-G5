<?php 
    require_once './views/layouts/header.php';
    require_once './views/layouts/side.php';
    
?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <div class="container">
        <form action="/purchase/update?id=<?= $item['id'] ?>" method="POST">
            <div class="form-group">
                <label for="" class="form-label">Name:</label>
                <input type="text" value=" <?= $item['name'] ?>" name="name" class="form-controll">
            </div>
            <button type="submit" class="btn btn-success mt-3">Update</button>
        </form>
    </div>
    <?php require_once 'views/layouts/footer.php' ?>
</main>