@props(['icon_size' => '25', 'icon_ratio' => '1/1'])

<div class="dropdown">
    <button class="bg-transparent border-0 dropdown-toggle " type="button" data-bs-toggle="dropdown"
        aria-label="Open Notifications">
        <picture class="">
            <source media="(min-width:2560px)" class=""
                srcset="{{ Vite::asset('resources/images/icons/notif-bell-69x69.webp') }}">
            <source media="(min-width:768px)" class=""
                srcset="{{ Vite::asset('resources/images/icons/notif-bell-69x69.webp') }}">

            <img class="icon" width="{{ $icon_size }}" height="{{ $icon_size }}"
                aspect-ratio="{{ $icon_ratio }}"
                src="{{ Vite::asset('resources/images/icons/notif-bell-35x35.webp') }}" alt="">
        </picture>
    </button>
    <ul class="dropdown-menu">
        {{ $slot }}
    </ul>
</div>
