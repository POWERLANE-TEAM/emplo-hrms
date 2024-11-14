{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Forms' tabular navigation bars. Not yet final.
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    ['title' => 'Pre-Employment Requirements', 'route' => 'config.form.pre-employment'],
    ['title' => 'Attendance Rules', 'route' => 'dashboard'],
    ['title' => 'Leaves', 'route' => 'dashboard'],
    ['title' => 'Overtime Summary Forms', 'route' => 'dashboard'],
    ['title' => 'Issues', 'route' => 'dashboard'],
]" />