{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Organization's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Department', 'route' => 'create-dept'],
    ['title' => 'Position', 'route' => 'create-position'],
]" />