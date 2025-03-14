<?php require_once './Views/layouts/header.php'; ?>
<div class="modal" id="productModal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Add New Product</h2>
        <form action="/purchase/store" method="POST" enctype="multipart/form-data">
            <label for="productName">Product Name:</label>
            <input type="text" id="productName" name="productName" required /><br />
            <label for="productImage">Upload Image:</label>
            <input type="file" id="productImage" name="productImage" accept="image/*" onchange="previewImage(event)" required /><br />
            <img id="imagePreview" class="preview-image" src="" alt="Image Preview" style="display: none;" /><br />
            <label for="productPrice">Price:</label>
            <input type="number" id="productPrice" name="productPrice" min="0" required /><br />
            <button type="submit">Submit</button>
        </form>
    </div>
</div>
<?php require_once './Views/layouts/footer.php'; ?>
