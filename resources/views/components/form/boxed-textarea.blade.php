{{-- 
* |-------------------------------------------------------------------------- 
* | Boxed: Text Area 
* |-------------------------------------------------------------------------- 
--}}

@props(['label', 'nonce', 'required' => false, 'rows' => 3, 'description' => null])

<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold">
    {{ $label }}
    {{-- Conditionally display the red asterisk for required fields --}}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>

{{-- Optional description below the label --}}
@if($description)
<p class="fs-7 mb-3">{!! $description !!}</p>
@endif

<div class="input-group mb-3 position-relative">
    <textarea @if($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif
              {{ $attributes->merge([
                  'class' => 'form-control border ps-3 rounded',
                  'autocomplete' => $attributes->get('autocomplete', 'off'),
                  'rows' => $rows
              ]) }}
              nonce="{{ $nonce }}"></textarea>
</div>
