@props([
    'icon_size' => '31px',
    'href' => '',
    'active' => false,
    'icon_ratio' => '1/1',
    'nav_txt' => '',
    'default_icon' => '',
    'active_icon' => '',
])

<li role="navigation" class="my-3 sidebar-item">
    <x-nav-link href="{{ $href }}" active="{{ $active }}" rel="" class="d-flex align-items-center ">
        <div class="nav-icon">

            @if (!empty($default_icon))
                <img width="{{ $icon_size }}" src="{{ Vite::asset($default_icon['src']) }}"
                    alt="{{ $default_icon['alt'] }}" aspect-ratio="{{ $icon_ratio }}" class="default">
            @endif
            @if (!empty($active_icon))
                <img width="{{ $icon_size }}" src="{{ Vite::asset($active_icon['src']) }}"
                    alt="{{ $active_icon['alt'] }}" aspect-ratio="{{ $icon_ratio }}" class="">
            @endif
        </div>
        <span class="nav-item">{{ $nav_txt }}</span>
        <span class="tooltip">{{ $nav_txt }}</span>
    </x-nav-link>

</li>
