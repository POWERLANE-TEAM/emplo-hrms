@props(['eventName'])

<style>
.modal-backdrop {
    display: none;
    z-index: 1040 !important;
}

.modal-content {
    margin: 2px auto;
    z-index: 1100 !important;
}
</style>

<div class="modal fade" id="{{ $eventName }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="{{ $eventName }}-label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="{{ $eventName }}">Edit Qualification</h1>
                <button @click="$dispatch('{{ $eventName }}-close')" type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button @click="$dispatch('{{ $eventName }}-close')" class="btn btn-secondary">Close</button>
                <button @click="$dispatch('{{ $eventName }}')" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
