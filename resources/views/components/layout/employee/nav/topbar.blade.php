@aware(['icon_size' => '25vw', 'icon_ratio' => '1/1'])

<div {{ $attributes->merge(['class' => '']) }}>
    <nav class="topnav">
        <div class="left d-flex align-items-center justify-content-end col-md-8 col-lg-6">
            <picture class=" ">
                <source media="(min-width:2560px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/search-51x51.webp') }}">
                <source media="(min-width:768px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/search-51x51.webp') }}">

                <img class="icon" width="{{ $icon_size }}" aspect-ratio="{{ $icon_ratio }}"
                    src="{{ Vite::asset('resources/images/icons/search-27x26.webp') }}" alt="">
            </picture>
            <x-form.search-group class="col-11 px-3" container_class="col-12">

                <x-form.search type="search" class=""
                    placeholder="Search job titles or companies"></x-form.search>

                <x-slot:right_icon>
                    <picture class="">
                        <source media="(min-width:2560px)" class=""
                            srcset="{{ Vite::asset('resources/images/icons/microphone-513x443.webp') }}">
                        <source media="(min-width:768px)" class=""
                            srcset="{{ Vite::asset('resources/images/icons/microphone-99x85.webp') }}">

                        <img class="icon" width="{{ $icon_size }}" aspect-ratio="aspect-ratio="{{ $icon_ratio }}"
                            src="{{ Vite::asset('resources/images/icons/microphone-60x61.webp') }}" alt="">
                    </picture>

                </x-slot:right_icon>

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
