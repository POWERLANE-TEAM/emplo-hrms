{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Performance Evaluation Result's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Overview', 'route' => 'probationary-perf-results'],
    ['title' => 'Da Records', 'route' => 'dashboard'],
    ['title' => 'Attendance', 'route' => 'dashboard'],
    ['title' => 'Comments', 'route' => 'dashboard'],
]" />