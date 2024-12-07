<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
    @endif
    class="bg-white rounded border p-3 shadow-sm cursor-pointer">

    <p class="small fw-medium mb-0">
        {{ $event['title'] }}
    </p>
    <p class="text-muted mt-2 small">
        {{ $event['description'] ?? 'No description' }}
    </p>
</div>
