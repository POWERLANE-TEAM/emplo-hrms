@props(['nonce', 'groupClass' => ''])

@isset($labelContent)
    <x-form.label id="{{ $attributes->get('id') }}" class="{{ $labelContent->attributes->get('class') }}">
        {{ $labelContent }}
    </x-form.label>
@endisset

<div class="input-group {{ $groupClass }}">

    <select {{ $attributes->merge(['class' => 'form-select']) }} x-data="{ selected: null }"
        x-on:change="if (!{{ $attributes->has('multiple') ? 'true' : 'false' }}) selected = $event.target.value">
        <template x-for="option in $el.options">
            <option :value="option.value"
                x-bind:disabled="!{{ $attributes->has('multiple') ? 'true' : 'false' }} && selected === option.value">
                <span x-text="option.text"></span>
            </option>
        </template>
        {{ $slot }}
    </select>

</div>
