@props(['overrideClass' => false])

<div
    {{ $attributes->merge(['class' => 'modal-body ' . ($overrideClass ? $attributes->get('class') : 'ps-md-5 pe-md-3 py-md-4 ' . $attributes->get('class'))]) }}>
    {{ $slot }}
</div>
