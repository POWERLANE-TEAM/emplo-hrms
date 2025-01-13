{{-- 
* |-------------------------------------------------------------------------- 
* | @include for HR Leaves
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Requests', 'route' => 'leaves.requests.general'],
]" />