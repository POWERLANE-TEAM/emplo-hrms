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

    <!-- <style>
        .has-no-choices {
            display: none;
        }

        .choices-custom,
        .choices__inner,
        select {
            width: 100% !important;
            border-radius: 5px;
            background-color: #fff;
        }

        .choices__list--dropdown {
            width: 100%;
            box-sizing: border-box;
            font-size: .5rem;
            border: 2px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            /* Adjust as needed */
            overflow-y: auto;
            background: #fff;
        }

        /* SELECTED ITEMS */
        .choices__list--multiple .choices__item {
            background-color: green;
            /* Transparent background */
            color: white;
            /* Black text color */
        }

        /* Remove the default grey background on first active item */
        .choices__list--dropdown .choices__item.is-highlighted {
            background-color: transparent !important;
            /* Remove grey background */
            color: inherit;
            /* Keep the original text color */
            outline: none;
            /* Remove any outline if present */
        }

        /* HOVER ON DROPDOWN ITEMS */
        .choices__list--dropdown .choices__item--selectable:hover {
            background-color: #FAFAFA !important;
            color: green;
            font-weight: 700;
        }


        /* Placeholder text styling */
        .choices__placeholder {
            color: #6c757d;
            font-style: italic;
        }

        /* Disabled items */
        .choices__item--disabled.custom-disabled {
            color: #888;
            background-color: #000;
        }

        /* Remove item button */
        .choices__button {
            background-color: transparent;
            color: #ff0000;
            font-weight: bold;
            cursor: pointer;
        }

        /* Customizing the search box */
        .choices__input {
            border: none;
            padding: 0;
            margin: 0;
        }


        /* Ensure that the dropdown takes full width */


        /* Adjust the input field within the dropdown for full width */
        .choices__input {
            width: 50% !important;
            /* Ensures placeholder text takes full width */
            padding-right: 30px;
            /* Adjust for remove button space if needed */
            background-color: white;
            box-sizing: border-box;
        }
    </style> -->
</div>

@once
    <script>
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


                            const placeholderOption = element.querySelector('option[value=""]');  // Select the placeholder option by value
                            console.log(placeholderOption);
                            if (selectedItems.length === totalOptions) {

                                placeholderOption.textContent = '';  // Clear the text of the placeholder option
                                console.log('Placeholder text removed');

                            } else {

                                placeholderOption.textContent = '{{ $attributes->get('placeholder', 'Select an option') }}';  // Reset text
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