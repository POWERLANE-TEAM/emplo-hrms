@props(['header' => ''])

<section class="mb-4">
    @if (! empty($header))
        <header class="fs-4 fw-bold mb-3" role="heading" aria-level="2">
            {{ $header }}
        </header>        
    @endif
    <div class="d-flex mb-5 row">
        {{ $slot }}
    </div>
</section>