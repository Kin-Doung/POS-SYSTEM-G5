<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php'; ?>

<body id="page-top">
    <div id="wrapper" style="margin-left: 250px;">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once './views/layouts/nav.php'; ?>
                <div class="container mt-5">
                    <h2 class="purchase-head">Edit Purchase</h2>
                    <form method="POST" action="/purchase/update/<?= htmlspecialchars($purchase['id']); ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" name="product_name" id="product_name" class="form-control" value="<?= htmlspecialchars($purchase['product_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['id']); ?>" <?= $category['id'] == $purchase['category_id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" name="barcode" id="barcode" class="form-control" value="<?= htmlspecialchars($purchase['barcode'] ?? ''); ?>">
                        </div>
                        <div class="mb-3" style="display: none;">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="<?= htmlspecialchars($purchase['quantity'] ?? 1); ?>" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <?php if ($purchase['image']): ?>
                                <img src="<?= htmlspecialchars($purchase['image']); ?>" alt="Current Image" width="100" class="mt-2">
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-success">Update Purchase</button>
                        <a href="/purchase" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .purchase-head {
            color: #1a3c34;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .btn-success, .btn-secondary {
            background-color: #1a3c34;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .btn-success:hover, .btn-secondary:hover {
            background-color: #152e2a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
    </style>
</body>
<?php require_once './views/layouts/footer.php'; ?>