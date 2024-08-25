@props(['icon_size' => '25vw', 'icon_ratio' => '1/1'])

<div>
    <div {{ $attributes->merge(['class' => ' topnav-mobile']) }}>
        <div>
            <button class="btn border-0 bg-transparent main-menu ">
                <x-icons.menu-fries></x-icons.menu-fries>
            </button>
        </div>
        <div class="d-flex w-100 justify-content-end align-items-center">
            <x-input.search-group class="col-9" container_class="col-12 y">

                <x-input.search type="search" class="bg-secondary"
                    placeholder="Search job titles or companies"></x-input.search>

                <x-slot:right_icon>
                    <button class="border-0 bg-transparent">
                        <i data-lucide="search"></i>
                    </button>
                </x-slot:right_icon>

            </x-input.search-group>

            <x-notif-dropdown>
                <i class="" data-lucide="bell"></i>
            </x-notif-dropdown>

        </div>
    </div>
</div>
