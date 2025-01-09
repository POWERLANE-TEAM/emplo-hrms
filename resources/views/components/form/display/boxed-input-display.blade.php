{{--
* |--------------------------------------------------------------------------
* | Boxed: Displaying Input Fields
* |--------------------------------------------------------------------------
--}}

@props([
    'label' => '',
    'data' => '',
    'description' => null,
    'tooltip' => null
])

<div class="mb-3">
    <label class="mb-1 fw-semibold text-secondary-emphasis">
        {{ $label }}
    </label>

    {{-- Optional description below the label --}}
    @if($description)
        <p class="fs-7 mb-3">{!! $description !!}</p>
    @endif

    {{-- Conditionally display the tooltip icon beside the label if tooltip and modalId are provided --}}
    @if($tooltip && isset($tooltip['modalId']))
        <x-tooltips.modal-tooltip icon="help-circle" color="text-info" modalId="{{ $tooltip['modalId'] }}" class="ms-2" />
    @endif

    <div class="border px-3 py-2 rounded">
        {{ $data }}
    </div>
</div>