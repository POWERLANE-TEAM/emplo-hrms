{{--
* |--------------------------------------------------------------------------
* | Boxed: Selectpicker (Allows typing in searching)
* |--------------------------------------------------------------------------
--}}

@props(['label' => null, 'options' => [], 'nonce', 'required' => false])

@if($label)
    <label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold text-secondary-emphasis">
        {{ $label }}
        {{-- Conditionally display the red asterisk for required fields --}}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif
<div wire:ignore class="input-group position-relative">
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

            new Choices(selectElement, {
                searchEnabled: true,
                itemSelectText: '',
                maxItemCount: 2,
                renderSelectedChoices: 'always',
                shouldSort: false, // Optional, can be changed :) Disables sorting if you want to retain original order
            });

            selectElement.setAttribute('data-choices-initialized', 'true');

            // Add custom scrollbar class after initialization
            // Use a small timeout to ensure the dropdown is fully initialized
            setTimeout(() => {
                // Directly select the .choices__list--dropdown element
                const dropdownList = document.querySelector('.choices__list--dropdown');
                if (dropdownList) {
                    dropdownList.classList.add('visible-gray-scrollbar');
                }
            }, 100);  // Adjust the timeout duration as necessary
        });
    });
</script>