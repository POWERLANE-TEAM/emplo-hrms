{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Performance Evaluation's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Categories', 'route' => 'config.performance.categories'],
    ['title' => 'Rating Scales', 'route' => 'config.performance.rating-scales'],
    ['title' => 'Period Settings', 'route' => 'config.performance.period-setup'],
]" />