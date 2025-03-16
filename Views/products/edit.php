<?php require_once './Views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <?php require_once './Views/layouts/header.php'; ?>
    <div class="modal-content">
        <!-- Assuming $product is passed from the controller -->
        <form method="POST" action="/products/updatePrice/<?= $product['id'] ?>">
            <label for="price">New Price:</label>
            <input type="number" name="price" value="<?= $product['price'] ?>" required>
            <button type="submit">Update Price</button>
        </form>
    </div>
    <?php require_once './Views/layouts/footer.php'; ?>
</main>