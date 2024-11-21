@props(['tabs' => []])

<div class="in-page-tab-nav">
    <div class="d-flex mb-3 tabs">
        @foreach ($tabs as $index => $tab)
            <a 
                href="#{{ $tab['section'] }}" 
                class="tab-link text-decoration-none me-4 fw-light text-muted" 
                data-section="{{ $tab['section'] }}">
                {{ $tab['title'] }}
            </a>
        @endforeach
    </div>
</div>
