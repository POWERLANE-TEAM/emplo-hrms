{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Organization's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Job Family', 'route' => 'job-family.index'],
    ['title' => 'Job Title', 'route' => 'job-title.index'],
]" />