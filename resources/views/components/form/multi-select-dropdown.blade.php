{{--
* |--------------------------------------------------------------------------
* | Boxed: Dropdowns
* |
* | Note: To select between a normal dropdown and multiselect, toggle the multiple prop
* |--------------------------------------------------------------------------
--}}

@props(['label', 'options' => [], 'nonce', 'required' => false])

<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold text-secondary-emphasis">
    {{ $label }}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>

<div wire:ignore class="input-group mb-3 position-relative col-12">
    <!-- Multi-select input with Choices.js integration -->
    <select
        @if($attributes->has('name')) 
            wire:model="{{ $attributes->get('name') }}" 
        @endif
        {{ $attributes->merge([
            'class' => 'form-control form-select border ps-3 rounded pe-5',
            'autocomplete' => $attributes->get('autocomplete', 'off'),
            'multiple' => true,
        ]) }} 
        nonce="{{ $nonce }}"
        id="{{ $attributes->get('id') }}">
        <option value="">{{ $attributes->get('placeholder', 'Select an option') }}</option>
        @foreach($options as $value => $optionLabel)
            <option value="{{ $value }}">{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>

@script
<script>
    document.addEventListener('livewire:navigated', function () {
        const element = document.getElementById('{{ $attributes->get("id") }}');

        if (element && element.hasAttribute('multiple')) {
            const choices = new Choices(element, {
                removeItemButton: true,
                placeholderValue: 'Select all that applies',
                searchPlaceholderValue: "Search...",
                shouldSort: false,
                noChoicesText: 'No choices to be selected',
                classNames: {
                    containerOuter: ['choices', 'choices-custom'],
                    itemSelectable: ['choices__item--selectable', 'custom-selectable'],
                    itemDisabled: ['choices__item--disabled', 'custom-disabled'],
                },
            });

            Livewire.on('changes-saved', () => {
                choices.removeActiveItems();
            });

            // Still trying to remove the Select an option here.
            setTimeout(function () {
                const choicesContainer = element.closest('.input-group').querySelector('.choices');

                if (choicesContainer) {
                    element.addEventListener('change', function () {
                        const noChoicesTextElement = document.querySelector('.has-no-choices');

                        const selectedItems = choices.getValue(true);
                        @this.set('{{ $attributes->get("name") }}', selectedItems);

                        const totalOptions = element.options.length - 1;
                        const placeholderOption = element.querySelector('option[value=""]'); 

                        if (selectedItems.length === totalOptions) {
                            placeholderOption.textContent = ''; 
                        } else {

                            placeholderOption.textContent = '{{ $attributes->get('placeholder', 'Select an option') }}';
                        }

                    });
                }
            }, 500); 
        }
    });
</script>
@endscript