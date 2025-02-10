
<div class="d-flex align-items-center">
    <button
        wire:click="exportAsExcel"
        type="button"
        class="btn btn-sm py-0 px-1 no-hover-border hover-opacity"
        data-bs-toggle="tooltip" title="Download as .xlsx">
        <i class="icon icon-slarge text-info" data-lucide="file-spreadsheet"></i>
    </button>

    <button
        wire:click="exportAsCsv"
        type="button"
        class="btn btn-sm py-0 px-1 no-hover-border hover-opacity"
        data-bs-toggle="tooltip" title="Download as .csv">
        <i class="icon icon-slarge text-info" data-lucide="file-down"></i>
    </button>
</div>