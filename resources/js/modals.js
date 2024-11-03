// ================================
// Modal Management Functions
// ================================

// Helper function to show the modal by ID
function showModal(modalId) {
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
}

// Helper function to hide the modal by ID
function hideModal(modalId) {
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    if (modal) {
        modal.hide();
    }
}


// ================================
// Edit Modal Functions
// ================================

// Common function to set input values and open modal for edit modals
function openEditModalWithData(modalId, inputId, itemName, selectId = null, priority = null) {
    document.getElementById(inputId).value = itemName;
    if (selectId && priority !== null) {
        document.getElementById(selectId).value = priority;
    }
    showModal(modalId);
}

// Define the openEditModal function and attach to window
export function openEditModal(itemName, index) {
    window.currentEditIndex = index;
    openEditModalWithData('editModalId', 'editItemInput', itemName);
}
window.openEditModal = openEditModal;

// Define the openEditQualificationModal function and attach to window
export function openEditQualificationModal(itemName, index, priority) {
    window.currentEditQualificationIndex = index;
    openEditModalWithData('editQualificationModalId', 'editQualificationInput', itemName, 'qualificationSelect', priority);
}
window.openEditQualificationModal = openEditQualificationModal;



// ================================
// Close Modal Functions
// ================================

// Define closeEditModal function and attach to window
export function closeEditModal() {
    hideModal('editModalId');
}
window.closeEditModal = closeEditModal;

// Define closeEditQualificationModal function and attach to window
export function closeEditQualificationModal() {
    hideModal('editQualificationModalId');
}
window.closeEditQualificationModal = closeEditQualificationModal;



// ================================
// Save Changes Functions
// ================================

// Common function to emit Livewire events and hide modals
function emitSaveChanges(modalId, emitEvent, itemName, index, priority = null) {
    if (priority !== null) {
        Livewire.emit(emitEvent, itemName, priority, index);
    } else {
        Livewire.emit(emitEvent, itemName, index);
    }
    hideModal(modalId);
}

// Define saveChanges function for general items and attach to window
export function saveChanges() {
    const itemName = document.getElementById('editItemInput').value;
    emitSaveChanges('editModalId', 'saveChanges', itemName, window.currentEditIndex);
}
window.saveChanges = saveChanges;

// Define saveQualificationChanges function and attach to window
export function saveQualificationChanges() {
    const itemName = document.getElementById('editQualificationInput').value;
    const priority = document.getElementById('qualificationSelect').value;
    emitSaveChanges('editQualificationModalId', 'saveQualificationChanges', itemName, window.currentEditQualificationIndex, priority);
}
window.saveQualificationChanges = saveQualificationChanges;
