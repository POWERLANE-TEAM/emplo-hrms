@props(['nonce', 'color_type' => 'primary'])

<div class="input-group mb-3 terms-condition">
    <input type="checkbox"
        {{ $attributes->merge([
            'class' => "checkbox checkbox-$color_type",
            // 'autocomplete' => $attributes->get('autocomplete', 'off'),
        ]) }}
        aria-owns="{{ $attributes->get('id') }}-feedback" wire:model="{{ $attributes->get('name') }}"
        nonce="{{ $nonce }}">

    @if (!empty($label))
        <label for="{{ $attributes->get('id') }}" class="checkbox-label d-flex flex-wrap">
            {{ $label }}
        </label>
    @endif


    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>
