@props([
    'modalId' => 'ratingScaleModal',
    'toastId' => 'ratingScaleToast'
])

<section>
    {{-- Modal --}}
    <div wire:ignore.self class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">{{ __($title) }}</h1>
                    <button wire:click="restart" data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>        
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="scale" class="col-form-label">{{ __('Rating Scale:') }}</label>
                            <input wire:model="state.scale" type="number" minlength="0" id="scale" class="form-control @error('state.scale') is-invalid @enderror" />
                            @error('state.scale')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                            
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="ratingName" class="col-form-label">{{ __('Rating Name:') }}</label>
                            <input wire:model="state.name" type="text" id="ratingName" class="form-control @error('state.name') is-invalid @enderror" />
                            @error('state.name')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button wire:click="restart" wire:loading.attr="disabled" data-bs-toggle="modal" class="btn btn-secondary">{{ __('Close') }}</button>
                    <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>    
    </div>

    {{-- Toast --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="{{ $toastId }}" class="toast text-bg-primary text-white top-25 end-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <p>{{ __('The assigned numerical ratings used to rate an employee\'s performance in a category.') }}</p>

    {{-- Add Category Button --}}
    <x-buttons.dotted-btn-open-modal :label="'Add Performance Scale'" :modal="$modalId" :disabled="false" />

    {{-- Draggable Grid Table --}}
    <div id="sortable-list" class="list-group">
        @foreach($this->ratings as $rating)
            <div class="list-group-item d-flex align-items-center mb-2 border border-secondary py-2 px-3 rounded"
                draggable="true"
                ondragstart="handleDragStart(event, this, '{{ $rating->id }}')"
                ondragover="event.preventDefault()"
                ondrop="drop(event, '{{ $rating->id }}')"
                ondragend="handleDragEnd(this)"
            >
                <div class="col-10 p-2">
                    <span class='fw-bold text-primary'>{{ $rating->scale }}=</span>
                    <span class='text-muted'>{{ $rating->name }}</span>
                </div>

                <div class="d-flex col-2 justify-content-end">
                    <button class="btn no-hover-border me-2"
                        wire:loading.attr="disabled"
                        wire:click="openEditMode( {{ $rating->id }} )"                        
                        data-bs-toggle="tooltip" title="Edit">
                        <i class="icon p-1 mx-2 text-info" data-lucide="pencil"></i>
                    </button>
                    <button class="btn no-hover-border" data-bs-toggle="tooltip" title="Drag" draggable="true">
                        <i class="icon p-1 mx-2 text-black" data-lucide="menu"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</section>

@script
<script>
    const modalEl = document.getElementById('{{ $modalId }}');
    const ratingScaleModal = new bootstrap.Modal(modalEl);
    const toastEl = document.getElementById('{{ $toastId }}');
    const toast = new bootstrap.Toast(toastEl);

    Livewire.hook('morph.added', ({ el }) => {
        lucide.createIcons();
    });

    Livewire.on('open-rating-scale-modal', () => {
        ratingScaleModal.show();
    });

    Livewire.on('changes-saved', (event) => {
        ratingScaleModal.hide();

        setTimeout(() => {
            toastEl.querySelector('.toast-body').textContent = event[0].feedbackMsg;
            toast.show();
        }, 1000);        
    });

    Livewire.on('not-found', (event) => {
        ratingScaleModal.hide();

        setTimeout(() => {
            toastEl.classList.replace('text-bg-primary', 'text-bg-danger');
            toastEl.querySelector('.toast-body').textContent = event[0].feedbackMsg;
            toast.show();
        }, 1000);        
    });

    Livewire.on('reset-rating-scale-modal', () => {
        ratingScaleModal.hide();
    });
</script>
@endscript