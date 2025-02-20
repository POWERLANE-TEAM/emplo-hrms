@props([
    'id' => null,
    'modalSize' => 'modal-lg',
    'isCentered' => true,
])

<div 
    wire:ignore.self 
    class="modal fade" 
    id="{{ $id }}" 
    aria-labelledby="{{ $id }}-label"
    {{ $attributes->merge([
        'data-bs-backdrop' => 'true',
        'data-bs-keyboard' => 'true'])
    }}
>
    <div class="modal-dialog {{ $modalSize }} {{ $isCentered ? 'modal-dialog-centered' : 'modal-dialog' }}" role="document">
        <div class="modal-content p-2">
            <div class="modal-header">
                {{ $title }}
            </div>
            <div class="modal-body">
                {{ $content }}
            </div>
            <div class="modal-footer">
                {{ $footer }}
            </div>
        </div>
    </div>    
</div>
