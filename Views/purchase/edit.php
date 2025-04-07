<form method="POST" action="/purchase/update/<?= $purchase['id']; ?>" enctype="multipart/form-data">
    <!-- Form fields for product_name, category_id, image -->
    <input type="text" name="product_name" value="<?= htmlspecialchars($purchase['product_name']); ?>">
    <select name="category_id">
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id']; ?>" <?= $category['id'] == $purchase['category_id'] ? 'selected' : ''; ?>>
                <?= $category['name']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="file" name="image">
    <button type="submit">Save</button>
</form>