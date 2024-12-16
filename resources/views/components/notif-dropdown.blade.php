@props(['iconSize' => '25', 'iconRatio' => '1/1'])

<div class="dropdown">
    @if (!request()->routeIs($routePrefix . '.notifications'))
        <button class="bg-transparent border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-label="Open Notifications">
            <picture class="">
                <source media="(min-width:2560px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/notif-bell-69x69.webp') }}">
                <source media="(min-width:768px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/notif-bell-69x69.webp') }}">

                <img class="icon" width="{{ $iconSize }}" height="{{ $iconSize }}" aspect-ratio="{{ $iconRatio }}"
                    src="{{ Vite::asset('resources/images/icons/notif-bell-35x35.webp') }}" alt="">
            </picture>
        </button>
        <div class="dropdown-menu dropdown-menu-left">
            {{ $slot }}
        </div>
    @else
        <button class="notif-icon-static border-0 rounded-circle pe-none w-100 h-auto object-fit-cover aspect-ratio--square"
            type="button">
            <i data-lucide="bell-ring" class="text-primary icon icon-large"></i>
        </button>
    @endif
</div>