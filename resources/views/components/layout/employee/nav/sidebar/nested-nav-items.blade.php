@props([
    'href' => '',
    'active' => false,
    'nav_txt' => '',
    'defaultIcon' => '',
    'activeIcon' => '',
    'src_path' => 'resources/images/icons/sidebar/',
    'src_extn' => '.webp',
    'children' => [], // Pass an array of child items
    'isActiveClosure' => null,
])

@aware(['iconSize' => '23px', 'iconRatio' => '1/1'])

<li role="none" {{ $attributes->merge(['class' => 'my-1 sidebar-item']) }}>
    <x-nav-link href="{{ $href }}" active="{{ $active }}" rel="" class="d-flex align-items-center nav-link pe-none"
        role="menuitem">
        <div class="nav-icon">
            @if (!empty($defaultIcon))
                <img width="{{ $iconSize }}" height="{{ $iconSize }}"
                    src="{{ Vite::asset($src_path . 'white-' . $defaultIcon['src'] . $src_extn) }}"
                    alt="{{ $defaultIcon['alt'] }}" aspect-ratio="{{ $iconRatio }}" class="default">
            @endif
            @if (!empty($activeIcon))
                <img width="{{ $iconSize }}" height="{{ $iconSize }}"
                    src="{{ Vite::asset($src_path . 'green-' . $defaultIcon['src'] . $src_extn) }}"
                    alt="{{ $activeIcon['alt'] }}" aspect-ratio="{{ $iconRatio }}" class="">
            @endif
        </div>
        <span class="nav-item fs-7">{{ $nav_txt }}</span>
        <span class="tooltip">{{ $nav_txt }}</span>
    </x-nav-link>

    {{-- Render children if they exist --}}
    @if (!empty($children))
        <ul class="nested-menu list-unstyled ">
            @foreach ($children as $child)
                @php
                    //  Determine if the current route matches the child's route
                    $isActive = url()->current() == $child['href'];
                    // dump($child['href']);
                    // dump($isActive);

                    // Optional additional check for active state
                    if ($isActive && $isActiveClosure instanceof \Closure) {
                        $isActive = $isActiveClosure($isActive, $child);
                    }

                    // dump($child['href']);
                    // dump($isActive);
                @endphp

                <li class="nested-item {{ $isActive ? 'active' : '' }}
                    {{ $isActive ? 'hovered' : '' }}" style="position: relative; padding-left: 20px;">

                    {{-- Vertical Line  --}}

                    <x-nav-link href="{{ $child['href'] }}" active="{{ $isActive }}"
                        class="d-flex align-items-center nav-link">
                        <span class="vertical-menu-line"></span>

                        <span class="nested-item-text fs-7 fw-light">{{ $child['nav_txt'] }}</span>
                    </x-nav-link>
                </li>
            @endforeach
        </ul>
    @endif
</li>
