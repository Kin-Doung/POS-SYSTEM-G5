<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <?php require_once './views/layouts/header.php'; ?>
    <div class="modal-content">
        <!-- Assuming $product is passed from the controller -->
        <form method="POST" action="/purhcase/updatePrice/<?= $purchase['id'] ?>">
            <label for="price">New Price:</label>
            <input type="number" name="price" value="<?= $purchase['price'] ?>" required>
            <button type="submit">Update Price</button>
        </form>
    </div>
    <?php require_once './views/layouts/footer.php'; ?>
</main>