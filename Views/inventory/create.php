
<div class="container-fluid d-flex justify-content-center align-items-center vh-100">
    <div class="card w-100" style="max-width: 600px;">
        <div class="col-md-8 mt-5">
            <h5 class="card-title text-center">Add New Product</h5>

            <form action="/inventory/store" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image" required>
                </div>

                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                </div>

                <div class="form-group">
                    <label for="price">Price ($)</label>
                    <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="expiration_date">Expiration Date</label>
                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Add Product</button>
            </form>
        </div>
    </div>
</div>
