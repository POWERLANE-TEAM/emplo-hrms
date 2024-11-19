@use(App\Enums\JobQualificationPriorityLevel)
{{-- 
* |-------------------------------------------------------------------------- 
* | Drag & Drop Qualifications Data
* |-------------------------------------------------------------------------- 
--}}

<div>
    <x-modals.edit-qualification :eventName="$eventName">
        <div class="mb-3">
            <label for="$eventName" class="col-form-label">{{ __('Qualification Name:') }}</label>
            <input wire:model="qualification" type="text" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="qualificationSelect" class="col-form-label">{{ __('Priority:') }}</label>

            <select id="qualificationSelect" class="form-select form-control" wire:model="priority">
                @foreach ($this->priorityLevels as $priorityLevel => $index)
                    <option value="{{ $index }}">{{ $priorityLevel }}</option>
                @endforeach
            </select>
        </div>
    </x-modals.edit-qualification>
    
    <div id="sortable-list" class="list-group">
        @foreach($items as $index => $item)
            @if(is_array($item) && isset($item['text'], $item['priority']))

                @php
                    $color = match($item['priority']) {
                        'hp' => 'danger',
                        'mp' => 'warning',
                        'lp' => 'success',
                        default => 'secondary',
                    };

                    $priority = JobQualificationPriorityLevel::tryFrom($item['priority'])
                @endphp

                <div class="list-group-item d-flex align-items-center mb-2 border border-secondary py-2 px-3 rounded" 
                    draggable="true"
                    ondragstart="handleDragStart(event, this, '{{ $index }}')" 
                    ondragover="event.preventDefault()"
                    ondrop="drop(event, '{{ $index }}')"
                    ondragend="handleDragEnd(this)">

                    <div class="col-8">{{ $item['text'] }}</div>
                    
                    <!-- Use status-badge component to display priority with color -->
                    <div class="col-2">
                        <x-status-badge :color="$color">
                            {{ $priority->label() }}
                        </x-status-badge>
                    </div>

                    <!-- Buttons with col-2 -->
                    <div class="col-2 d-flex justify-content-end">
                        <button wire:click="loadQualification( {{ $index }} )" data-bs-toggle="tooltip" class="btn no-hover-border me-2" data-bs-title="Edit">
                            <i class="icon p-1 mx-2 text-info" data-lucide="pencil"></i>
                        </button>
                        <button class="btn no-hover-border" data-bs-dismiss="modal" data-bs-toggle="tooltip" data-bs-title="Drag" draggable="true">
                            <i class="icon p-1 mx-2 text-black" data-lucide="menu"></i>
                        </button>
                    </div>
                </div>
            @else
                <div class="list-group-item text-danger">Invalid item format</div>
            @endif
        @endforeach
    </div>
</div>

@script
<script>
    Livewire.hook('morph.added',  ({ el }) => {
        lucide.createIcons();
    });

    const modalEl = document.getElementById('{{ $eventName }}');
    const qualificationModal = new bootstrap.Modal(modalEl);

    $wire.on('open-modal', () => {
        qualificationModal.show();
    });

    $wire.on('save-changes-close', () => {
        qualificationModal.hide();
    });
</script>
@endscript