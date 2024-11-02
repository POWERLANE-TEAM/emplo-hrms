<div>
    <div id="sortable-list">
        @foreach($items as $index => $item)
            <div class="d-flex align-items-center mb-2" draggable="true"
                ondragstart="event.dataTransfer.setData('text/plain', '{{ $index }}')" ondragover="event.preventDefault()"
                ondrop="drop(event, '{{ $index }}')">
                <div class="col-10">{{ $item }}</div>
                <button class="btn btn-warning col-1" onclick="openEditModal('{{ $item }}', {{ $index }})">Edit</button>
                <button class="btn btn-primary col-1">Drag</button>
            </div>
        @endforeach
    </div>
</div>

<script>

    function drop(event, index) {
        event.preventDefault();
        const draggedIndex = event.dataTransfer.getData('text/plain');
        const sortableList = document.getElementById('sortable-list');
        const draggedElement = sortableList.children[draggedIndex];

        // Insert dragged element before or after the drop target
        if (index < draggedIndex) {
            sortableList.insertBefore(draggedElement, sortableList.children[index]);
        } else {
            sortableList.insertBefore(draggedElement, sortableList.children[index + 1]);
        }

        // Update Livewire items with only the necessary text
        const newOrder = Array.from(sortableList.children).map(child => child.querySelector('.col-10').innerText.trim());
        @this.set('items', newOrder);
    }
</script>