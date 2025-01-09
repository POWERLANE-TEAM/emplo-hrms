@aware(['iconSize' => '25', 'iconRatio' => '1/1'])

<div>
    <div {{ $attributes->merge(['class' => ' topnav-mobile']) }}>
        <div>
            <button class="btn border-0 bg-transparent main-menu ">
                <x-icons.menu-fries></x-icons.menu-fries>
            </button>
        </div>
        <div class="d-flex w-100 justify-content-end align-items-center">
            <x-form.search-group class="col-9" container_class="col-12 y">

                <x-form.search type="search" class="bg-secondary" form="Search job titles or companies"></x-form.search>

                <x-slot:right_icon>
                    <button class="border-0 bg-transparent">
                        <i data-lucide="search"></i>
                    </button>
                </x-slot:right_icon>

            </x-form.search-group>

            <x-notif-dropdown>
                <li class="dropdown-item">Notif 1</li>
                <li class="dropdown-item">Notif 1</li>
                <li class="dropdown-item">Notif 1</li>
            </x-notif-dropdown>

        </div>
    </div>
</div>
