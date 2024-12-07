<div
    ondragenter="onLivewireCalendarEventDragEnter(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragleave="onLivewireCalendarEventDragLeave(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragover="onLivewireCalendarEventDragOver(event);"
    ondrop="onLivewireCalendarEventDrop(event, '{{ $componentId }}', '{{ $day }}', {{ $day->year }}, {{ $day->month }}, {{ $day->day }}, '{{ $dragAndDropClasses }}');"
    class="flex-grow-1 border position-relative"
    style="min-width: 10rem; height: 10rem;"> 

    {{-- Wrapper for Drag and Drop --}}
    <div class="w-100 h-100" id="{{ $componentId }}-{{ $day }}">

        <div
            @if($dayClickEnabled)
                wire:click="onDayClick({{ $day->year }}, {{ $day->month }}, {{ $day->day }})"
            @endif
            class="w-100 h-100 p-2 d-flex flex-column {{ $dayInMonth ? ($isToday ? 'bg-warning' : 'bg-white') : 'bg-light' }}">

            {{-- Number of Day --}}
            <div class="d-flex align-items-center">
                <p class="small {{ $dayInMonth ? 'fw-medium' : '' }} mb-0">
                    {{ $day->format('j') }}
                </p>
                <p class="text-muted ms-2 mb-0 small">
                    @if($events->isNotEmpty())
                        {{ $events->count() }} {{ Str::plural('event', $events->count()) }}
                    @endif
                </p>
            </div>

            {{-- Events --}}
            <div class="p-2 my-2 flex-grow-1 overflow-auto">
                <div class="d-grid gap-2">
                    @foreach($events as $event)
                        <div
                            @if($dragAndDropEnabled)
                                draggable="true"
                            @endif
                            ondragstart="onLivewireCalendarEventDragStart(event, '{{ $event['id'] }}')">
                            @include($eventView, [
                                'event' => $event,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
