<script>
    Livewire.on('changes-saved', (event) => {
        console.log('Event object:', event);
        const modalId = event[0].modalId;

        if (modalId) {
            const modalEl = document.getElementById(modalId);
            if (modalEl) {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                modalInstance.hide();
                console.log(`Modal with ID ${modalId} hidden successfully.`);
            } else {
                console.error(`Modal with ID ${modalId} not found!`);
            }
        } else {
            console.error('Modal ID not found in event data');
        }
    });
</script>
