{{-- 
* |-------------------------------------------------------------------------- 
* | Drag & Drop Qualifications Data
* |-------------------------------------------------------------------------- 
--}}

<div id="sortable-list" class="list-group">
    @foreach($items as $index => $item)
        @if(is_array($item) && isset($item['text'], $item['priority']))
            @php
                // Determine the color based on the priority
                $color = match($item['priority']) {
                    'High Priority' => 'danger',
                    'Medium Priority' => 'warning',
                    'Low Priority' => 'success',
                    default => 'secondary', // Default color if priority does not match
                };
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
                        {{ $item['priority'] }}
                    </x-status-badge>
                </div>

                <!-- Buttons with col-2 -->
                <div class="col-2 d-flex justify-content-end">
                    <button class="btn no-hover-border me-2" onclick="openEditQualificationModal('{{ $item['text'] }}', {{ $index }}, '{{ $item['priority'] }}')" data-bs-toggle="tooltip" title="Edit">
                        <i class="icon p-1 mx-2 text-info" data-lucide="pencil"></i>
                    </button>
                    <button class="btn no-hover-border" data-bs-toggle="tooltip" title="Drag" draggable="true">
                        <i class="icon p-1 mx-2 text-black" data-lucide="menu"></i>
                    </button>
                </div>
            </div>
        @else
            <div class="list-group-item text-danger">Invalid item format</div>
        @endif
    @endforeach
</div>
