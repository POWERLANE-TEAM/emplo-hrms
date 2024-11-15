<div id="sortable-list" class="list-group">
    @foreach($items as $index => $item)
        @if(is_array($item) && isset($item['head'], $item['subhead']))
            <div class="list-group-item d-flex align-items-center mb-2 border border-secondary py-2 px-3 rounded"
                 draggable="true"
                 ondragstart="handleDragStart(event, this, '{{ $index }}')"
                 ondragover="event.preventDefault()"
                 ondrop="drop(event, '{{ $index }}')"
                 ondragend="handleDragEnd(this)">

                <!-- Render prepared HTML for dataOne and dataTwo -->
                <div class="col-10 p-2">
                    {!! $head[$index] ?? $item['head'] !!}
                    {!! $subhead[$index] ?? $item['subhead'] !!}
                </div>

                <div class="d-flex col-2 justify-content-end">
                    <button class="btn no-hover-border me-2"
                        @if($editCallback) onclick="{{ $editCallback }}('{{ $item['head'] }}', {{ $index }}, '{{ $item['subhead'] }}')" @endif
                        data-bs-toggle="tooltip" title="Edit">
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
