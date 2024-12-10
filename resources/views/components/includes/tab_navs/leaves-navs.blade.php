{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Leaves Summary Form's navigation tabs.
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Recent Records', 'route' => 'overtimes.recents'],
    ['title' => 'Archive Records', 'route' => 'overtimes.index'],
]" />