<form action="/inventory/destroy/<?php echo $item['id']; ?>" method="POST">
    <p>Are you sure you want to delete <strong><?php echo $item['product_name']; ?></strong>?</p>
    <button type="submit">Delete</button>
</form>
