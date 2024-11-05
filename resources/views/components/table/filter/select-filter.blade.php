{{-- https://rappasoft.com/docs/laravel-livewire-tables/v3/datatable/configurable-areas#content-example-view --}}

@aware(['component'])

@php
    $wireMethods = $filter->getWireMethod('filterComponents.' . $filter->getKey());
    $wireMethod = 'filterComponents.' . $filter->getKey();
    $wireKey = $filter->generateWireKey($tableName, 'select');
    $inputId =
        $tableName .
        '-filter-' .
        $filter->getKey() .
        ($filter->hasCustomPosition() ? '-' . $filter->getCustomPosition() : '');
@endphp

<div x-data="{ open: false }" @keydown.window.escape="open = false" x-on:click.away="open = false"
    wire:key="inst-select-filter-{{ $component->getTableName() }}" class="align-content-md-center flex-wrap">
    <div class="d-flex gap-2 gap-md-3 flex-wrap">

        {{-- I dont know why is this causing error syntax error, unexpected token "endif", expecting end of file --}}
        {{-- $wireMethods value is wire:model.live=filterComponents.jobPosition --}}
        {{-- even {!! $wireMethods !!} has error --}}
        {{-- even ::{{ $wireMethods }} has error --}}
        {{-- even :{{ $wireMethods }} has error --}}
        <x-form.select groupClass="" :wire:model.live="$wireMethod" :wire:key="$wireKey" :id="$inputId"
            class="form-select">
            <x-slot:labelContent class="d-flex align-content-center flex-wrap align-middle">
                <span class="text-nowrap">Applicants for:</span>
            </x-slot>
            @foreach ($filter->getOptions() as $key => $value)
                @if (is_iterable($value))
                    <optgroup label="{{ $key }}">
                        @foreach ($value as $optionKey => $optionValue)
                            <option value="{{ $optionKey }}">{{ $optionValue }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $key }}">{{ $value }}</option>
                @endif
            @endforeach
        </x-form.select>
    </div>
</div>
