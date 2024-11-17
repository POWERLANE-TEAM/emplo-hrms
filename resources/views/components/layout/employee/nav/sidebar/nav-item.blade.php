@props([
    'href' => '',
    'active' => false,
    'nav_txt' => '',
    'default_icon' => '',
    'activeIcon' => '',
    'src_path' => 'resources/images/icons/sidebar/',
    'src_extn' => '.webp',
])

@aware(['iconSize' => '23px', 'iconRatio' => '1/1'])

<li role="none" {{ $attributes->merge(['class' => 'my-1 sidebar-item']) }}>
    <x-nav-link href="{{ $href }}" active="{{ $active }}" rel=""
        class="d-flex align-items-center nav-link " role="menuitem">
        <div class="nav-icon">

            @if (!empty($default_icon))
                <img width="{{ $iconSize }}" height="{{ $iconSize }}"
                    src="{{ Vite::asset($src_path . 'white-' . $default_icon['src'] . $src_extn) }}"
                    alt="{{ $default_icon['alt'] }}" aspect-ratio="{{ $iconRatio }}" class="default">
            @endif
            @if (!empty($activeIcon))
                <img width="{{ $iconSize }}" height="{{ $iconSize }}"
                    src="{{ Vite::asset($src_path . 'green-' . $default_icon['src'] . $src_extn) }}"
                    alt="{{ $activeIcon['alt'] }}" aspect-ratio="{{ $iconRatio }}" class="">
            @endif
        </div>
        <span class="nav-item fs-7">{{ $nav_txt }}</span>
        <span class="tooltip">{{ $nav_txt }}</span>
    </x-nav-link>

</li>
