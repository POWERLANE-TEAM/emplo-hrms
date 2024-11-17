@aware(['icon_size' => '15', 'icon_ratio' => '1/1'])

<picture>
    <source media="(min-width:1200px)" class=""
        srcset="{{ Vite::asset('resources/images/icons/white-search-1-xl.webp') }}">

    <source media="(min-width:576px)" class=""
        srcset="{{ Vite::asset('resources/images/icons/white-search-1-sm.webp') }}">

    <source media="(max-width:320px)" class=""
        srcset="{{ Vite::asset('resources/images/icons/white-search-1-xs.webp') }}">

    <img width="{{ $icon_size }}" height="{{ $icon_size }}" aspect-ratio="{{ $icon_ratio }}" class=""
        loading="lazy" src="{{ Vite::asset('resources/images/icons/white-search-1-sm.webp') }}" alt="">
</picture>
