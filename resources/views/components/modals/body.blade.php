@props(['overrideClass' => false])

<div
    {{ $attributes->merge(['class' => 'modal-body ' . ($overrideClass ? $attributes->get('class') : 'px-md-5 py-md-4 ' . $attributes->get('class'))]) }}>
    {{ $slot }}
</div>
