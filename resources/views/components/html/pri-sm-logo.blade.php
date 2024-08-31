@props(['icon_size' => '30px', 'icon_ratio' => '1/1'])

<picture>
    <source media="(min-width:2560px)" class=""
        srcset="{{ Vite::asset('resources/images/logo/Powerlane-513x445.png') }}">
    <source media="(min-width:1200px)" class=""
        srcset="{{ Vite::asset('resources/images/logo/Powerlane-151x131.png') }}">
    <source media="(min-width:768px)" class=""
        srcset="{{ Vite::asset('resources/images/logo/Powerlane-121x105.png') }}">
    <source media="(min-width:576px)" class=""
        srcset="{{ Vite::asset('resources/images/logo/Powerlane-91x79.png') }}">
    <source media="(max-width:320px)" class=""
        srcset="{{ Vite::asset('resources/images/logo/Powerlane-63x53.png') }}">

    <img width="{{ $icon_size }}" height="{{ $icon_size }}" aspect-ratio="aspect-ratio="{{ $icon_ratio }}"
        class="pri-sm-logo" loading="lazy" src="{{ Vite::asset('resources/images/logo/Powerlane-91x79.png') }}"
        alt="">
</picture>
