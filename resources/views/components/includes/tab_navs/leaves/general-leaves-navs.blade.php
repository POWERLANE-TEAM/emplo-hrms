{{-- 
* |-------------------------------------------------------------------------- 
* | @include for General Leaves
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Requests', 'route' => 'leaves.index'],
]" />