export function openEditModal(itemName, index) {
    document.getElementById('editItemInput').value = itemName;
    window.currentEditIndex = index; 
    const modal = new bootstrap.Modal(document.getElementById('editModalId'));
    modal.show();
}

export function closeEditModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('editModalId'));
    if (modal) {
        modal.hide();
    }
}

export function saveChanges() {
    const itemName = document.getElementById('editItemInput').value;
    Livewire.emit('saveChanges', itemName, window.currentEditIndex);
    const modal = bootstrap.Modal.getInstance(document.getElementById('editModalId'));
    if (modal) {
        modal.hide();
    }
}

window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;
window.saveChanges = saveChanges;