{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Leaves Summary Form's navigation tabs.
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Summary Form', 'route' => 'overtime.summary-form'],
    ['title' => 'Overtime Requests', 'route' => 'overtime.requests'],
]" />