@props([
    'name' => __('Unknown Employee'),
    'photo' => null,
    'id' => null,
])

<div class="d-flex align-items-center">
    <img src="{{ $photo }}" alt="{{ "{$name}-avatar" }}" class="rounded-circle me-3" style="width: 38px; height: 38px">
    <div>
        <div> {{ $name }} </div>
        <div class="text-muted fs-6">
            {{ __("Employee No. {$id}") }}
        </div>
    </div>
</div>