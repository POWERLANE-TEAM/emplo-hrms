@props(['icon_size' => '25vw', 'icon_ratio' => '1/1'])

<div {{ $attributes->merge(['class' => 'align-content-center topnav-mobile']) }}>
    <div>
        <button class="btn border-0 bg-transparent main-menu">
            <x-icons.menu-fries></x-icons.menu-fries>
        </button>
    </div>
    <div class="d-flex ms-auto align-items-center">
        <x-input.search-group class="col-11 px-3" container_class="col-12 y">

            <x-input.search type="search" class="bg-secondary"
                placeholder="Search job titles or companies"></x-input.search>

            <x-slot:right_icon>
                <button class="border-0 bg-transparent">
                    <i data-lucide="search"></i>
                </button>
            </x-slot:right_icon>

        </x-input.search-group>

        <x-notif-dropdown>
            <i class="text-black" data-lucide="bell"></i>
        </x-notif-dropdown>

    </div>
</div>
