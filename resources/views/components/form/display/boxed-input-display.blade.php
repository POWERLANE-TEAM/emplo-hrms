{{--
* |--------------------------------------------------------------------------
* | Boxed: Displaying Input Fields
* |--------------------------------------------------------------------------
--}}

@props([
    'label' => '',
    'data' => '',  
])

<div class="mb-3">
    <label class="mb-1 fw-semibold text-secondary-emphasis">
        {{ $label }}
    </label>

    <div class="border px-3 py-2 rounded">
        {{ $data }}
    </div>
</div>
