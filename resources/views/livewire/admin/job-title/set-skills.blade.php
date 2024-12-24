@use(App\Enums\JobQualificationPriorityLevel)
@props(['modalId' => 'qualificationsModal'])

<div class="mt-4">

    <x-headings.form-snippet-intro label="{{ __('Skills') }}" :nonce="$nonce" required="true">

    </x-headings.form-snippet-intro>

    <x-modals.dialog :id="$modalId">
        <x-slot:title>
            <h1 class="modal-title fs-5">{{ __('Edit Qualification') }}</h1>
            <button wire:click="discard" data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>
        <x-slot:content>
            <div class="mb-3">
                <label for="$eventName" class="col-form-label">{{ __('Qualification Name:') }}</label>
                <input wire:model="editState.qualification" type="text" class="form-control" />
            </div>
            <div class="mb-3">
                <label for="qualificationSelect" class="col-form-label">{{ __('Priority:') }}</label>
                <select id="qualificationSelect" class="form-select form-control" wire:model="editState.priority">
                    @foreach ($this->priorityLevels as $priorityLevel => $index)
                        <option value="{{ $index }}">{{ $priorityLevel }}</option>
                    @endforeach
                </select>
            </div>
        </x-slot:content>
        <x-slot:footer wire:loading.attr="disabled">
            <button wire:click="discard" class="btn btn-secondary">{{ __('Close') }}</button>
            <button wire:click="save" class="btn btn-primary">{{ __('Save changes') }}</button>
        </x-slot:footer>
    </x-modals.dialog>

    <div id="sortable-list" class="list-group">
        @foreach($items as $index => $item)
                @php
                    $color = match ($item['priority']) {
                        'hp' => 'danger',
                        'mp' => 'warning',
                        'lp' => 'success',
                        default => 'secondary',
                    };

                    $priority = JobQualificationPriorityLevel::tryFrom($item['priority'])
                @endphp

                <div wire:ignore.self
                    class="list-group-item d-flex align-items-center mb-2 border border-secondary py-2 px-3 rounded"
                    draggable="true" ondragstart="handleDragStart(event, this, '{{ $index }}')"
                    ondragover="event.preventDefault()" ondrop="drop(event, '{{ $index }}')" ondragend="handleDragEnd(this)">

                    <div class="col-8">{{ $item['qualification'] }}</div>

                    <!-- Use status-badge component to display priority with color -->
                    <div class="col-2">
                        <x-status-badge :color="$color">
                            {{ $priority->label() }}
                        </x-status-badge>
                    </div>

                    <!-- Buttons with col-2 -->
                    <div class="col-2 d-flex justify-content-end">

                        <!-- Edit -->
                        <button wire:click="openEditMode( {{ $index }} )" data-bs-toggle="tooltip"
                            class="btn no-hover-border me-2" data-bs-title="Edit">
                            <i class="icon p-1 mx-2 text-info" data-lucide="pencil"></i>
                        </button>

                        <!-- Delete -->
                        <button data-bs-toggle="tooltip"
                            class="btn no-hover-border me-2" data-bs-title="Delete">
                            <i class="icon p-1 mx-2 text-danger" data-lucide="trash-2"></i>
                        </button>

                        <!-- Move -->
                        <button class="btn no-hover-border" data-bs-dismiss="modal" data-bs-toggle="tooltip"
                            data-bs-title="Drag" draggable="true">
                            <i class="icon p-1 mx-2 text-black" data-lucide="menu"></i>
                        </button>
                    </div>
                </div>
        @endforeach
    </div>

    <div x-data="{ showInput: false }" x-cloak>
        <!-- Button to show the input field and dropdown -->
        <button x-show="!showInput" @click="showInput = true" type="button"
            class="btn w-100 border-dashed py-2 text-primary">
            {{ __('Add Skills Qualifications') }}
        </button>

        <!-- Input field and dropdown; only shown when showInput is true -->
        <div x-show="showInput" class="mt-2">
            <div class="mb-2 mt-3 green-divider"></div>

            <div class="row align-items-center py-3">
                <!-- Text input field -->
                <div class="col-7">
                    <input id="qualification-input" name="qualification" class="form-control border ps-3 rounded"
                        placeholder="{{ __('E.g., Public speaking skills.') }}" wire:model="state.qualification"
                        autocomplete="off">

                    @error('state.qualification')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Dropdown for priority -->
                <div class="col-3 position-relative">
                    <select class="form-select form-control border ps-3 rounded pe-5" wire:model="state.priority"
                        aria-label="Qualification options">
                        <option value="">{{ __('Select Priority') }}</option>
                        @foreach ($this->priorityLevels as $priorityLevel => $index)
                            <option value="{{ $index }}">{{ $priorityLevel }}</option>
                        @endforeach
                    </select>
                    @error('state.priority')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="col-2 d-flex">
                    <button type="button" class="btn btn-success me-2 text-white flex-fill" wire:loading.attr="disabled"
                        wire:click="save">
                        {{ __('Save') }}
                    </button>
                    <button @click.prevent="showInput = false" wire:loading.attr="disabled" type="button"
                        class="btn btn-secondary flex-fill">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    Livewire.hook('morph.added', ({ el }) => {
        lucide.createIcons();
    });

    const modalEl = document.getElementById('{{ $modalId }}');
    const qualificationModal = new bootstrap.Modal(modalEl);

    Livewire.on('open-qualification-modal', () => {
        qualificationModal.show();
    });

    Livewire.on('close-qualification-modal', () => {
        qualificationModal.hide();
    });

    Livewire.on('save-changes-close', () => {
        qualificationModal.hide();
    });
</script>
@endscript