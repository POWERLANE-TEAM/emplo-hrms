{{--
* |--------------------------------------------------------------------------
* | Dotted Button for Opening Modal
* |
* |--------------------------------------------------------------------------
--}}


@props(['label' => 'Submit', 'nonce' => $nonce, 'id' => null, 'loading' => 'Processing...', 'disabled' => true, 'modal' => ''])

<button type="button"
    class="btn w-100 border-dashed py-2 text-primary mb-3"
    data-bs-toggle="modal"
    data-bs-target="#{{ $modal }}"
    {{ $nonce }}
    {{ $disabled ? 'disabled' : '' }}
>
    {{ $label }}
</button>

