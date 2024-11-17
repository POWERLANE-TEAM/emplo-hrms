@aware(['iconSize' => '25', 'iconRatio' => '1/1'])

<picture>
    <source media="(min-width:1200px)" class=""
        srcset="{{ Vite::asset('resources/images/icons/white-search-1-xl.webp') }}">

    <source media="(min-width:576px)" class=""
        srcset="{{ Vite::asset('resources/images/icons/white-search-1-sm.webp') }}">

    <source media="(max-width:320px)" class=""
        srcset="{{ Vite::asset('resources/images/icons/white-search-1-xs.webp') }}">

    <img width="{{ $iconSize }}" height="{{ $iconSize }}" aspect-ratio="{{ $iconRatio }}" class=""
        loading="lazy" src="{{ Vite::asset('resources/images/icons/white-search-1-sm.webp') }}" alt="">
</picture>
