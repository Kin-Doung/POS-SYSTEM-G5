

<h2 class="text-center">Edit Inventory Item</h2>
<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <!-- Center the form using Bootstrap's flex utilities -->
    <div class="row justify-content-center w-100">
        <div class="col-md-6">
            <form action="/inventory/update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $inventoryItem['id']; ?>">
                <input type="hidden" name="current_image" value="<?php echo $inventoryItem['image']; ?>">

                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name:</label>
                    <input type="text" name="product_name" class="form-control" value="<?php echo $inventoryItem['product_name']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image:</label>
                    <?php if ($inventoryItem['image']): ?>
                        <img src="<?php echo $inventoryItem['image']; ?>" alt="Current Image" class="img-thumbnail" style="width: 150px;"><br><br>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" name="quantity" class="form-control" value="<?php echo $inventoryItem['quantity']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount:</label>
                    <input type="number" name="amount" class="form-control" value="<?php echo $inventoryItem['amount']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="expiration_date" class="form-label">Expiration Date:</label>
                    <input type="date" name="expiration_date" class="form-control" value="<?php echo $inventoryItem['expiration_date']; ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Item</button>
            </form>
        </div>
    </div>
</div>

