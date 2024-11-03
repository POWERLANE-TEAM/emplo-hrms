{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Forms' tabular navigation bars. Not yet final.
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.tabular-nav :guard="$guard" :items="[
    ['title' => 'Pre-Employment Requirements', 'route' => 'pre-emp-reqs'],
    ['title' => 'Attendance Rules', 'route' => 'create-position'],
    ['title' => 'Leaves', 'route' => 'create-position'],
    ['title' => 'Overtime Summary Forms', 'route' => 'create-position'],
    ['title' => 'Issues', 'route' => 'create-position'],
]" />