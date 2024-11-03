<div class="modal fade" id="editQualificationModalId" tabindex="-1" aria-labelledby="editQualificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editQualificationModalLabel">Edit Qualification</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editQualificationInput" class="col-form-label">Qualification Name:</label>
                    <input type="text" id="editQualificationInput" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="qualificationSelect" class="col-form-label">Priority:</label>

                    <!-- Placeholder datas list. Change. -->
                    <select id="qualificationSelect" class="form-select form-control">
                        <option value="High Priority">High Priority</option>
                        <option value="Medium Priority">Medium Priority</option>
                        <option value="Low Priority">Low Priority</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="closeEditQualificationModal()" class="btn btn-secondary">Close</button>
                <button onclick="saveQualificationChanges()" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
