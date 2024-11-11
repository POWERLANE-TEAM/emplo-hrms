{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Organization's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Job Family', 'route' => 'create-dept'],
    ['title' => 'Job Title', 'route' => 'create-position'],
]" />