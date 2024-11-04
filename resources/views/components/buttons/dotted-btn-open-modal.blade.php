{{--
* |--------------------------------------------------------------------------
* | Dotted Button for Opening Modal
* |
* |--------------------------------------------------------------------------
--}}


@props(['label' => 'Submit', 'nonce' => '', 'id' => null, 'loading' => 'Processing...', 'disabled' => true, 'modal' => ''])

<button type="button"
        class="btn w-100 border-dashed py-2 text-primary"
        onclick="openModal('{{ $modal }}')"
        {{ $disabled ? 'disabled' : '' }}>
    {{ $label }}
</button>

