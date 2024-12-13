{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Settings & Privacy Page
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    [
        'title' => 'Recent Records',
        'icon' => 'shield-plus',
        'route' => 'profile',
    ],
    [
        'title' => 'Activity Log',
        'icon' => 'list',
        'route' => 'notifications',
    ],
]" />