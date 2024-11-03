document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        const tooltip = new bootstrap.Tooltip(tooltipTriggerEl);

        // Hide tooltip on click
        tooltipTriggerEl.addEventListener('click', function () {
            tooltip.hide();
        });

        // Hide tooltip on drag
        tooltipTriggerEl.addEventListener('dragstart', function () {
            tooltip.hide(); 
        });

         // Hide tooltip on drag end
         tooltipTriggerEl.addEventListener('dragend', function () {
            tooltip.hide(); 
        });
    });
});
