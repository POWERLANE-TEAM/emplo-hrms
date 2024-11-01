{{--
* |--------------------------------------------------------------------------
* | Boxed: Input Field
* |--------------------------------------------------------------------------
--}}

@props(['label', 'nonce', 'required' => false,])

<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold">
    {{ $label }}
    {{-- Conditionally display the red asterisk for required fields --}}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
<div class="input-group mb-3 position-relative">
    <!-- Input with boxed styling -->
    <input 
        wire:model="{{ $attributes->get('name') }}" 
        {{ $attributes->merge([
            'class' => 'form-control border ps-3 rounded',
            'autocomplete' => $attributes->get('autocomplete', 'off'),
            'placeholder' => $attributes->get('placeholder', '')  // Add placeholder attribute
        ]) }} 
        nonce="{{ $nonce }}">
</div>
