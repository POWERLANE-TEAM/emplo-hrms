@props(['nonce', 'color_type' => 'primary', 'container_class'])

<div class="position-relative d-flex {{ $container_class ?? '' }}">
    <input type="checkbox"
        {{ $attributes->merge([
            'class' => "checkbox checkbox-$color_type",
            // 'autocomplete' => $attributes->get('autocomplete', 'off'),
        ]) }}
        id="{{ $attributes->get('id') }}"
        @if (isset($feedback)) aria-owns="{{ $attributes->get('id') }}-feedback" @endif
        @if ($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif nonce="{{ $nonce }}">

    @if (!empty($label))
        <label for="{{ $attributes->get('id') }}" class="checkbox-label">
            {{ $label }}
        </label>
    @endif


</div>
@if (!empty($feedback))
    {{ $feedback }}
@endif
