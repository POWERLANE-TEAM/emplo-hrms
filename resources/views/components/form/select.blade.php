@props(['nonce', 'groupClass' => ''])

{{-- {{ dd($attributes) }} --}}
{{-- {{ dd($labelContent) }} --}}

{{-- {{ dd($labelContent->attributes) }} --}}
@isset($labelContent)
    <x-form.label id="{{ $attributes->get('id') }}" class="{{ $labelContent->attributes->get('class') }}">
        {{ $labelContent }}
    </x-form.label>
@endisset

<div class="input-group {{ $groupClass }}">

    <select id="{{ $attributes->get('id') }}" class="form-select {{ $attributes->get('class') ?? '' }}">
        {{ $slot }}
    </select>

</div>
