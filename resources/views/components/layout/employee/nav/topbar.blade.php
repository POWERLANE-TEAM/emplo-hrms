@aware(['iconSize' => '12vw', 'iconRatio' => '1/1'])

<div {{ $attributes->merge(['class' => '']) }}>
    <nav class="topnav main-body">
        <div class="left d-flex align-items-center justify-content-end col-md-8 col-lg-6">
            <picture class=" ">
                <source media="(min-width:2560px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/search-51x51.webp') }}">
                <source media="(min-width:768px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/search-51x51.webp') }}">

                <img class="icon" width="{{ $iconSize }}" aspect-ratio="{{ $iconRatio }}"
                    src="{{ Vite::asset('resources/images/icons/search-27x26.webp') }}" alt="">
            </picture>
            <x-form.search-group class="col-11 px-3" container_class="col-12">

                <x-form.search type="search" class="" placeholder="Search page..."></x-form.search>

            </x-form.search-group>

        </div>

        <div class="right d-flex justify-content-end align-items-center col-md-4 col-lg-6">

            @if (!empty($topbar_right))
                {{ $topbar_right }}
            @endif

        </div>
    </nav>
</div>

<x-layout.employee.nav.topbar-mobile class="">

</x-layout.employee.nav.topbar-mobile>
