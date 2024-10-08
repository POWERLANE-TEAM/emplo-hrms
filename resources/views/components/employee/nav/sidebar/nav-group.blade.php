@props([
    'sidebar_expanded' => true,
    'txt_collapsed' => '',
    'txt_expanded' => '',
    'icon_size' => '31px',
    'icon_ratio' => '1/1',
])

<header class="small text-uppercase nav-head">
    <span class="small ">{{ $txt_collapsed }} <span class=" small truncate">{{ $txt_expanded }}</span></span>
</header>
<nav class="ps-2">
    <ul role="menu">

        {{ $slot }}

    </ul>
</nav>
