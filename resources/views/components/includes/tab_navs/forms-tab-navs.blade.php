{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Forms' tabular navigation bars. Not yet final.
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Pre-Employment Requirements', 'route' => 'config.form.pre-employment'],
]" />