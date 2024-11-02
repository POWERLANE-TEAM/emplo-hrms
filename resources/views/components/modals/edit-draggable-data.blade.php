<div class="modal fade" id="editModalId" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Edit Item</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editItemInput" class="col-form-label">Item Name:</label>
                    <input type="text" id="editItemInput" class="form-control" />
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="closeEditModal()" class="btn btn-secondary">Close</button>
                <button onclick="saveChanges()" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

