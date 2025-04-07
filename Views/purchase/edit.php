<h1>Helll</h1>

<div class="container mt-4">
    <h2>Edit Purchase</h2>
    <form action="/purchase/update" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $purchase['id'] ?>">
    <input type="text" name="product_name" value="<?= $purchase['product_name'] ?>">
    <select name="category_id">
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>" <?= $category['id'] == $purchase['category_id'] ? 'selected' : '' ?>>
                <?= $category['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="file" name="image">
    <button type="submit">Update</button>
</form>


</div>