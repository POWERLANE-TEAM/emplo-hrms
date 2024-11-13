{{--
* |--------------------------------------------------------------------------
* | Boxed: Dropdowns
* |
* | Note: To select between a normal dropdown and multiselect, toggle the multiple prop
* |--------------------------------------------------------------------------
--}}

@props(['label', 'options' => [], 'nonce', 'required' => false, 'multiple' => false])

<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold text-secondary-emphasis">
    {{ $label }}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>

<div class="input-group mb-3 position-relative col-12">
    <!-- Multi-select input with Choices.js integration -->
    <select @if($attributes->has('name')) wire:model="{{ $attributes->get('name') }}{{ $multiple ? '.*' : '' }}" @endif
        {{ $attributes->merge([
    'class' => 'form-control form-select border ps-3 rounded pe-5',
    'autocomplete' => $attributes->get('autocomplete', 'off'),
    'multiple' => $multiple ? true : false,
]) }} nonce="{{ $nonce }}"
        id="{{ $attributes->get('id') }}">
        <option value="">{{ $attributes->get('placeholder', 'Select an option') }}</option>
        @foreach($options as $value => $optionLabel)
            <option value="{{ $value }}">{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>

@once
    <script>
        // Necessary script to trigger the multi select.
        document.addEventListener('DOMContentLoaded', function () {
            const element = document.getElementById('{{ $attributes->get("id") }}');

            if (element && element.hasAttribute('multiple')) {
                const choices = new Choices(element, {
                    removeItemButton: true,
                    placeholderValue: '',
                    searchPlaceholderValue: "Search...",
                    shouldSort: false,
                    noChoicesText: 'No choices to be selected',
                    classNames: {
                        containerOuter: ['choices', 'choices-custom'],
                        itemSelectable: ['choices__item--selectable', 'custom-selectable'],
                        itemDisabled: ['choices__item--disabled', 'custom-disabled'],
                    },
                });

                // Still trying to remove the Select an option here.
                setTimeout(function () {
                    const choicesContainer = element.closest('.input-group').querySelector('.choices');
                    console.log('Choices container:', choicesContainer);

                    if (choicesContainer) {
                        element.addEventListener('change', function () {
                            const noChoicesTextElement = document.querySelector('.has-no-choices');

                            const selectedItems = choices.getValue(true);  // Get selected items from Choices.js instance
                            console.log('Selected items:', selectedItems);

                            const totalOptions = element.options.length - 1;;
                            console.log('Total options:', totalOptions);


                            const placeholderOption = element.querySelector('option[value=""]'); 
                            console.log(placeholderOption);
                            if (selectedItems.length === totalOptions) {

                                placeholderOption.textContent = ''; 
                                console.log('Placeholder text removed');

                            } else {

                                placeholderOption.textContent = '{{ $attributes->get('placeholder', 'Select an option') }}';
                            }

                        });
                    } else {
                        console.log('Choices container not found');
                    }
                }, 500); 
            }
        });
    </script>
@endonce