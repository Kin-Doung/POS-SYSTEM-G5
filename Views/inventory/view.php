
<h2>Inventory Item Details</h2>

<div class="inventory-item">
    <p><strong>Product Name:</strong> <?php echo htmlspecialchars($inventoryItem['product_name']); ?></p>
    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($inventoryItem['quantity']); ?></p>
    <p><strong>Amount:</strong> <?php echo htmlspecialchars($inventoryItem['amount']); ?></p>
    <p><strong>Expiration Date:</strong> <?php echo htmlspecialchars($inventoryItem['expiration_date']); ?></p>

    <?php if ($inventoryItem['image']): ?>
        <img src="<?php echo htmlspecialchars($inventoryItem['image']); ?>" alt="Product Image" style="width: 150px;">
    <?php endif; ?>

    <br><br>
    <a href="/inventory" class="btn btn-secondary">Go Back</a>
</div>
