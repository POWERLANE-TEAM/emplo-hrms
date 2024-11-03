{{-- 
* |-------------------------------------------------------------------------- 
* | Boxed: Dropdown Input Field 
* |-------------------------------------------------------------------------- 
--}}

@props(['label', 'options' => [], 'nonce', 'required' => false,])

<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold">
    {{ $label }}
    {{-- Conditionally display the red asterisk for required fields --}}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
<div class="input-group mb-3 position-relative">
    <!-- Dropdown input with boxed styling -->
    <select 
        @if($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif 
        {{ $attributes->merge([
            'class' => 'form-control form-select border ps-3 rounded pe-5',
            'autocomplete' => $attributes->get('autocomplete', 'off'),
        ]) }} 
        nonce="{{ $nonce }}">
        <option value="">{{ $attributes->get('placeholder', 'Select an option') }}</option>
        @foreach($options as $value => $optionLabel)
            <option value="{{ $value }}">{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>
