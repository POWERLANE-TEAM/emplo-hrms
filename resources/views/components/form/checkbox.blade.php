@props(['nonce', 'color_type' => 'primary', 'container_class'])

<div class="position-relative d-flex {{ $container_class ?? '' }}">
    <input type="checkbox"
        {{ $attributes->merge([
            'class' => "checkbox checkbox-$color_type",
            // 'autocomplete' => $attributes->get('autocomplete', 'off'),
        ]) }}
        @if (isset($feedback)) aria-owns="{{ $attributes->get('id') }}-feedback" @endif
        wire:model="{{ $attributes->get('name') }}" nonce="{{ $nonce }}">

    @if (!empty($label))
        <label for="{{ $attributes->get('id') }}" class="checkbox-label d-flex flex-wrap">
            {{ $label }}
        </label>
    @endif


    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>
