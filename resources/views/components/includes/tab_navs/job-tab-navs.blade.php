{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Organization's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Job Family', 'route' => 'job-family.create'],
    ['title' => 'Job Title', 'route' => 'job-titles.create'],
]" />