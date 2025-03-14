<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/nav.php'; ?>

<h2>Add New Purchase</h2>
<form action="/purchase/create" method="POST">
    <label>Product Name:</label>
    <input type="text" name="product_name" required><br/>

    <label>Quantity:</label>
    <input type="number" name="quantity" required><br/>

    <label>Price:</label>
    <input type="number" name="price" step="0.01" required><br/>

    <label>Purchase Date:</label>
    <input type="date" name="purchase_date" required><br/>

    <button type="submit">Submit</button>
</form>

<?php require_once '../layouts/footer.php'; ?>
