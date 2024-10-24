@props([
    'href' => '',
    'active' => false,
    'nav_txt' => '',
    'default_icon' => '',
    'active_icon' => '',
    'src_path' => 'resources/images/icons/',
    'src_extn' => '.webp',
])

@aware(['icon_size' => '31px', 'icon_ratio' => '1/1'])

<li role="none" {{ $attributes->merge(['class' => 'my-3 sidebar-item']) }}>
    <x-nav-link href="{{ $href }}" active="{{ $active }}" rel=""
        class="d-flex align-items-center nav-link " role="menuitem">
        <div class="nav-icon">

            @if (!empty($default_icon))
                <img width="{{ $icon_size }}" height="{{ $icon_size }}"
                    src="{{ Vite::asset($src_path . 'white-' . $default_icon['src'] . $src_extn) }}"
                    alt="{{ $default_icon['alt'] }}" aspect-ratio="{{ $icon_ratio }}" class="default">
            @endif
            @if (!empty($active_icon))
                <img width="{{ $icon_size }}" height="{{ $icon_size }}"
                    src="{{ Vite::asset($src_path . 'green-' . $default_icon['src'] . $src_extn) }}"
                    alt="{{ $active_icon['alt'] }}" aspect-ratio="{{ $icon_ratio }}" class="">
            @endif
        </div>
        <span class="nav-item">{{ $nav_txt }}</span>
        <span class="tooltip">{{ $nav_txt }}</span>
    </x-nav-link>

</li>
