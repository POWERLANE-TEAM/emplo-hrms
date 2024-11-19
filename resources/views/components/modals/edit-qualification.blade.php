@props(['eventName'])

<div class="modal fade" id="{{ $eventName }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="{{ $eventName }}-label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="{{ $eventName }}">{{ __('Edit Qualification') }}</h1>
                <button @click="$dispatch('{{ $eventName }}-close')" type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button @click="$dispatch('{{ $eventName }}-close')" class="btn btn-secondary">{{ __('Close') }}</button>
                <button @click="$dispatch('{{ $eventName }}')" class="btn btn-primary">{{ __('Save changes') }}</button>
            </div>
        </div>
    </div>
</div>
