@props(['modalId' => 'preemploymentModal'])

<section>
    {{-- Modal --}}
    <x-modals.dialog :id="$modalId">
        <x-slot:title>
            <h1 class="modal-title fs-5">{{ __('Edit Item') }}</h1>
            <button wire:click="restart" data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>
        <x-slot:content>
            <div class="mb-3">
                <label for="editItemInput" class="col-form-label">{{ __('Item Name:') }}</label>
                <input wire:model="state.existingRequirement" type="text" id="editItemInput" class="form-control" />
                @error('state.existingRequirement')
                    <div class="invalid-feedback" role="alert"> {{ $message }} </div>
                @enderror
            </div>
        </x-slot:content>
        <x-slot:footer wire:loading.attr="disabled">
            <button wire:click="discard" data-bs-toggle="modal" class="btn btn-secondary">{{ __('Discard') }}</button>
            <button wire:click="save" class="btn btn-primary">{{ __('Save changes') }}</button>
        </x-slot:footer>
    </x-modals.dialog>
    <p class="py-2">{{ __('Pre-employment requirements are the list of documents or attachments that pre-employed applicants needs
        to submit before proceeding.') }}</p>

    <div class="mb-4">
        <div x-data="{ showInput: false }" x-cloak>
            <button x-show="!showInput" @click="showInput = true" type="button"
                class="btn w-100 border-dashed py-2 text-primary">
                {{ __('Add A Pre-Employment Requirement') }}
            </button>

            <div x-show="showInput" class="mt-2">
                <div class="mt-3 green-divider"></div>

                <div class="row align-items-center py-3">
                    <div class="col-10">
                        <input wire:model="state.newRequirement" class="form-control border ps-3 rounded"
                            placeholder="Type item here..." autocomplete="off" />
                        @error('state.newRequirement')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-2 d-flex">
                        <button wire:click="save" wire:loading.attr="disabled" type="submit" class="btn btn-success me-2 text-white flex-fill">
                            {{ __('Go') }}
                        </button>
                        <button wire:loading.attr="disabled" @click.prevent="showInput = false" type="button" class="btn btn-secondary flex-fill">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid Table of Pre-Emp Requirements --}}    
    <div id="sortable-list" class="list-group">
        @foreach($this->requirements as $requirement)
            <div class="list-group-item d-flex align-items-center mb-2 border border-secondary py-2 px-3 rounded"
                draggable="true"
                ondragstart="handleDragStart(event, this, '{{ $requirement->id }}')"
                ondragover="event.preventDefault()"
                ondrop="drop(event, '{{ $requirement->id }}')"
                ondragend="handleDragEnd(this)"
            >
                <div class="col-10">{{ $requirement->name }}</div>

                <div class="d-flex col-2 justify-content-end">
                    <button class="btn no-hover-border me-2"
                        wire:click="openEditMode( {{ $requirement->id }} )"                        
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
    const categoryModal = new bootstrap.Modal(modalEl);

    Livewire.hook('morph.added', ({ el }) => {
        lucide.createIcons();
    });

    Livewire.on('open-preemployment-modal', () => {
        categoryModal.show();
    });

    Livewire.on('changes-saved', () => {
        categoryModal.hide();
    });

    Livewire.on('reset-preemployment-modal', () => {
        categoryModal.hide();
    });
</script>
@endscript