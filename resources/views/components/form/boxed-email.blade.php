{{--
* |--------------------------------------------------------------------------
* | Boxed: Email
* |--------------------------------------------------------------------------
--}}

@props(['label', 'nonce', 'required' => false])

<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold text-secondary-emphasis">
    {{ $label }}
    {{-- Conditionally display the red asterisk for required fields --}}
    @if ($required)
        <span class="text-danger">*</span>
    @endif
</label>

<div class="input-group position-relative">
    <input type="email"
        {{ $attributes->merge([
            'class' => 'form-control border ps-3 rounded',
            'autocomplete' => $attributes->get('autocomplete', 'off'),
            'pattern' => $attributes->get('pattern', '/^[a-zA-Z0-9._\-]+@[a-z0-9.-]+\.[a-z]{2,4}$/'),
            'placeholder' => $attributes->get('placeholder', 'e.g. ' . 'alex.a@gmail.com'),
        ]) }}
        aria-owns="{{ $attributes->get('id') }}-feedback" maxlength="320" required
        @if ($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif nonce="{{ $nonce }}">

</div>
@if (!empty($feedback))
    {{ $feedback }}
@endif
