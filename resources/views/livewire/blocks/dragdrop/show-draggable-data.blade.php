{{-- 
* |-------------------------------------------------------------------------- 
* | Drag & Drop Table Data
* |-------------------------------------------------------------------------- 
--}}

<div id="sortable-list" class="list-group">
    @foreach($items as $index => $item)
        <div class="list-group-item d-flex align-items-center mb-2 border border-secondary py-2 px-3 rounded" 
             draggable="true"
             ondragstart="handleDragStart(event, this, '{{ $index }}')" 
             ondragover="event.preventDefault()"
             ondrop="drop(event, '{{ $index }}')"
             ondragend="handleDragEnd(this)">
             
            <div class="col-10">{{ $item }}</div>
            
            <!-- Buttons with col-2 -->
            <div class="col-2 d-flex justify-content-end">
                <button class="btn no-hover-border me-2" onclick="openEditModal('{{ $item }}', {{ $index }})" data-bs-toggle="tooltip" title="Edit">
                    <i class="icon p-1 mx-2 text-info" data-lucide="pencil"></i>
                </button>
                <button class="btn no-hover-border" data-bs-toggle="tooltip" title="Drag"  draggable="true">
                    <i class="icon p-1 mx-2 text-black" data-lucide="menu"></i>
                </button>
            </div>
        </div>
    @endforeach
</div>

