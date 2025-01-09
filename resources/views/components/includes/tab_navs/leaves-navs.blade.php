{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Leaves Summary Form's navigation tabs.
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Summary Forms', 'route' => 'overtimes.summaries'],
    ['title' => 'Recent Records', 'route' => 'overtimes.recents'],
    ['title' => 'Archive Records', 'route' => 'overtimes.archive'],
]" />