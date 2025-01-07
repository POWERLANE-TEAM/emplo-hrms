@props([
    'sidebar_expanded' => true,
    'txt_collapsed' => '',
    'txt_expanded' => '',
])
@aware(['iconSize' => '31px', 'iconRatio' => '1/1'])


@if (!empty($slot) && trim($slot) != '')
    <header class="small text-uppercase nav-head">
        <span class="small collapse-text">{{ $txt_collapsed }}</span>
        <span class="small truncate text-nowrap">{{ $txt_expanded }}</span>
    </header>
    <nav class="ps-2">
        <ul class="d-grid" role="menu">

            {{ $slot }}

        </ul>
    </nav>
@endif
