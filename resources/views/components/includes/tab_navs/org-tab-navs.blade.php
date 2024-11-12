{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Organization's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Job Family', 'route' => 'create-job-family'],
    ['title' => 'Job Title', 'route' => 'create-job-title'],
]" />