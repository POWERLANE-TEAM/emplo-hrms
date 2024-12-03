{{--
* |--------------------------------------------------------------------------
* | Boxed: Dropdown Input Field
* |--------------------------------------------------------------------------
--}}

@props(['label' => null, 'options' => [], 'nonce', 'label' => null, 'required' => false, 'tooltip' => null])

@if($label)
<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold text-secondary-emphasis">
    {{ $label }}
    {{-- Conditionally display the red asterisk for required fields --}}
    @if($required)
        <span class="text-danger">*</span>
    @endif

    {{-- Conditionally display the tooltip icon beside the label if tooltip and modalId are provided --}}
    @if($tooltip && isset($tooltip['modalId']))
        <x-tooltips.modal-tooltip icon="help-circle" color="text-info" modalId="{{ $tooltip['modalId'] }}" class="ms-2" />
    @endif

</label>
@endif

<div class="input-group mb-3 position-relative">
    <!-- Dropdown input with boxed styling -->
    <select @if($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif {{ $attributes->merge([
    'class' => 'form-control form-select border ps-3 rounded pe-5',
    'autocomplete' => $attributes->get('autocomplete', 'off'),
]) }} nonce="{{ $nonce }}">
        <option value="">{{ $attributes->get('placeholder', 'Select an option') }}</option>
        @foreach($options as $value => $optionLabel)
            <option value="{{ $value }}">{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>