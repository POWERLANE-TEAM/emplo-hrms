{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Settings & Privacy Page
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    [
        'title' => 'Security',
        'icon' => 'shield-plus',
        'route' => 'settings',
    ],
    [
        'title' => 'Activity Log',
        'icon' => 'list',
        'route' => 'activity-logs',
    ],
]" />