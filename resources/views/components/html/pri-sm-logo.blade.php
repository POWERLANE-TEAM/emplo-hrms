@props(['iconSize' => '30px', 'iconRatio' => '1/1'])

<picture>
    <source media="(min-width:2560px)" class=""
        srcset="{{ Vite::asset('resources/images/logo/powerlane-xxl.webp') }}">
    <source media="(min-width:1200px)" class=""
        srcset="{{ Vite::asset('resources/images/logo/powerlane-xl.webp') }}">
    <source media="(min-width:992px)" class="" srcset="{{ Vite::asset('resources/images/logo/powerlane-lg.webp') }}">
    <source media="(min-width:768px)" class=""
        srcset="{{ Vite::asset('resources/images/logo/powerlane-md.webp') }}">
    <source media="(min-width:576px)" class=""
        srcset="{{ Vite::asset('resources/images/logo/powerlane-sm.webp') }}">
    <source media="(max-width:320px)" class=""
        srcset="{{ Vite::asset('resources/images/logo/powerlane-xs.webp') }}">

    <img width="{{ $iconSize }}" height="{{ $iconSize }}" aspect-ratio="{{ $iconRatio }}" class="pri-sm-logo"
        loading="lazy" src="{{ Vite::asset('resources/images/logo/powerlane-md.webp') }}" alt="">
</picture>
