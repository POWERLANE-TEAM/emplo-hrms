@props([
    'sidebar_expanded' => true,
    'txt_collapsed' => '',
    'txt_expanded' => '',
    'icon_size' => '31px',
    'icon_ratio' => '1/1',
])

<header class="small text-uppercase nav-group">
    <span class="small ">{{ $txt_collapsed }} <span class=" small group-header">{{ $txt_expanded }}</span></span>
</header>
<nav class="ps-2">
    <ul>

        {{ $slot }}

    </ul>
</nav>
