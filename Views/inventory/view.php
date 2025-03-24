
<div class="modal-dialog">
<h2>View Inventory Item</h2>

<p><strong>Product Name:</strong> <?php echo htmlspecialchars($inventory['product_name']); ?></p>
<p><strong>Category:</strong> <?php echo htmlspecialchars($inventory['category_name']); ?></p>
<p><strong>Quantity:</strong> <?php echo htmlspecialchars($inventory['quantity']); ?></p>
<p><strong>Amount:</strong> $<?php echo htmlspecialchars($inventory['amount']); ?></p>
<p><strong>Expiration Date:</strong> <?php echo htmlspecialchars($inventory['expiration_date']); ?></p>

<?php if (!empty($inventory['image'])): ?>
    <p><strong>Image:</strong></p>
    <img src="<?php echo htmlspecialchars($inventory['image']); ?>" alt="Product Image" width="200">
<?php endif; ?>

<br>
<a href="/inventory" class="btn btn-primary">Back to List</a>
</div>
 
