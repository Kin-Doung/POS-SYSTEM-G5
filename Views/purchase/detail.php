<?php require_once './Views/layouts/header.php' ?>

<div class="row">
    <div class="col-md-3">
        <div class="card">
        </div>
    </div>
    <div class="col-md-8 mt-5">
        <h3>Employee Details</h3>
        <table class="table table-bordered">
            <tr>
                <th>Quantity</th>
                <td><?= $purchase['quantity']  ?></td>
            </tr>
            <tr>
                <th>Product Name</th>
                <td><?= $purchase['product_name'] ?></td>
            </tr>
            <tr>
                <th>Price</th>
                <td><?= $purchase['price'] ?></td>
            </tr>
            <tr>
                <th>Date of time</th>
                <td><?= $purchase['purchase_date'] ?></td>
            </tr>
            <tr>
                <th>Department</th>
                <td><?= $prchase['department'] ?> </td>
            </tr>
        </table>
        <a href="/purchase" class="btn btn-secondary">Back to Puchase List</a>

    </div>
</div>

<?php require_once './Views/layouts/footer.php' ?>