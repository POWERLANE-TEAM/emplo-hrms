{{-- 
* |-------------------------------------------------------------------------- 
* | @include for HR Leaves
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Requests', 'route' => 'leaves.requests.general'],
    ['title' => 'Leave Balance', 'route' => 'leaves.balance.general'],
]" />