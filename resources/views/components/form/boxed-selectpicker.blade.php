{{--
* |--------------------------------------------------------------------------
* | Boxed: Selectpicker (Allows typing in searching)
* |--------------------------------------------------------------------------
--}}

@props(['label' => null, 'options' => [], 'nonce', 'required' => false])

<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold text-secondary-emphasis">
    {{ $label }}
    {{-- Conditionally display the red asterisk for required fields --}}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
<div class="input-group mb-3 position-relative">
    <!-- Dropdown input with boxed styling -->
    <select @if($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif {{ $attributes->merge([
    'class' => 'form-control form-select border ps-3 rounded pe-5 selectpicker',
    'autocomplete' => $attributes->get('autocomplete', 'off'),
]) }} nonce="{{ $nonce }}">
        <option value="">{{ $attributes->get('placeholder', 'Select an option') }}</option>
        @foreach($options as $value => $optionLabel)
            <option value="{{ $value }}">{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>

<script>

document.addEventListener('DOMContentLoaded', function () {
        const selectElements = document.querySelectorAll('.selectpicker');

        selectElements.forEach(function (selectElement) {
            // Check if the element has already been initialized using a data attribute
            if (selectElement.getAttribute('data-choices-initialized') === 'true') {
                return; // Skip initialization if already done
            }

            // Initialize Choices.js
            new Choices(selectElement, {
                searchEnabled: true,
                itemSelectText: '',
            });
            selectElement.setAttribute('data-choices-initialized', 'true');
        });
    });
</script>