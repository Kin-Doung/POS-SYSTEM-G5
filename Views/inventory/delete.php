<!-- Modal for deleting item -->
<div class="modal fade" id="inventory<?= $inventory['id'] ?>" tabindex="-1" aria-labelledby="itemLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemLabel">Delete Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <!-- Assuming you're displaying each inventory item here -->
                <form action="/inventory/delete" method="POST">
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                </form>

            </div>
        </div>
    </div>
</div>