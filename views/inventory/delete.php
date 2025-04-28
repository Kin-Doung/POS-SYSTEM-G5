<div class="modal fade" id="inventory<?= htmlspecialchars($inventory['id']) ?>" tabindex="-1" aria-labelledby="itemLabel<?= $inventory['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemLabel<?= $inventory['id'] ?>">Delete Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item (ID: <?= htmlspecialchars($inventory['id']) ?>)?
            </div>
            <div class="modal-footer">
                <form action="/inventory/destroy" method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($inventory['id']) ?>">
                    <?php if (isset($_SESSION['csrf_token'])): ?>
                        <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <?php endif; ?>
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>