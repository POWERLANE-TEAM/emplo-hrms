@props(['isHeading' => false, 'heading'])

<hgroup class="pt-2 mb-5 ms-n1">
    <div class="fs-2 fw-bold mb-2" {{ $isHeading ? 'role=heading aria-level=1' : '' }}>{{ $heading ?? '' }}</div>
    {{ $description ?? '' }}
</hgroup>
