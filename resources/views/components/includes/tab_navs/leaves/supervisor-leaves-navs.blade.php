{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Supervisor Leaves
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Requests', 'route' => 'leaves.requests'],
    ['title' => 'Leave Balance', 'route' => 'leaves.balance.subordinates'],
]" />