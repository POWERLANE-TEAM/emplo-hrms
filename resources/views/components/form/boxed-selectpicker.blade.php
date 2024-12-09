{{--
* |--------------------------------------------------------------------------
* | Boxed: Selectpicker (Allows typing in searching)
* |--------------------------------------------------------------------------
--}}

@props(['label' => null, 'options' => [], 'nonce', 'required' => false])

@if($label)
    <label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold text-secondary-emphasis">
        {{ $label }}

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

<style>
    .choices__placeholder {
        font-style: normal;
    }
</style>

<script>
    document.addEventListener('livewire:navigated', () => {
        const selectElements = document.querySelectorAll('.selectpicker');

        selectElements.forEach(function (selectElement) {
            if (selectElement.getAttribute('data-choices-initialized') === 'true') {
                return;
            }

            new Choices(selectElement, {
                searchEnabled: true,
                itemSelectText: '',
                maxItemCount: 2,
                renderSelectedChoices: 'always',
                shouldSort: false, // Optional, can be changed :) Disables sorting if you want to retain original order
            });

            selectElement.setAttribute('data-choices-initialized', 'true');

            setTimeout(() => {
                const dropdownList = document.querySelector('.choices__list--dropdown');
                if (dropdownList) {
                    dropdownList.classList.add('visible-gray-scrollbar');
                }
            }, 100);
        });
    });
</script>