{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Performance Evaluation's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$guard" :items="[
    ['title' => 'Categories', 'route' => 'categories'],
    ['title' => 'Performance Scales', 'route' => 'perf-scales'],
    ['title' => 'Scoring', 'route' => 'scoring'],
    ['title' => 'Passing Rate Range', 'route' => 'pass-rate-range'],
]" />