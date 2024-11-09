@props(['isHeading' => false, 'heading'])

<hgroup class="mb-5 mb-md-4 ms-md-n1">
    <div class="fs-3 fw-bold mb-2" {{ $isHeading ? 'role=heading aria-level=1' : '' }}>{{ $heading ?? '' }}</div>
    {{ $description ?? '' }}
</hgroup>
