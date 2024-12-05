{{--
* |--------------------------------------------------------------------------
* | Headings with Associated Link Buttons
* |--------------------------------------------------------------------------
--}}

@props(['heading', 'description', 'nonce', 'label' => null])

<div {{ $attributes->merge([
    'class' => "pt-2 pb-4 ms-n1",
]) }}>
    <div class="row">
        <div class="col-10">
            <div class="fs-2 fw-bold mb-2">{{ $heading }}</div>
            <div class="description">
                {{ $description }}
            </div>
        </div>

        <div class="col-2 d-flex align-items-center justify-content-end"> {{-- Flex to align the button --}}
            <x-buttons.link-btn :label="$label" class="btn-outline-primary" nonce="$nonce" href="{{ $attributes->get('href') }}" />
        </div>
    </div>
</div>