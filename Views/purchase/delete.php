<div class="modal fade" id="item<?= $item['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure to delete this user ?
            </div>
            <form method="POST" action="/purchase/destroy">
                <input type="hidden" name="id" value="<?= $item['id']; ?>" />
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>