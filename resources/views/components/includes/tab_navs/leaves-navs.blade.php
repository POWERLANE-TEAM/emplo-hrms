{{--
* |--------------------------------------------------------------------------
* | @include for Leaves Summary Form's navigation tabs.
* |--------------------------------------------------------------------------
--}}

@props(['view'])

<x-sub-navs.tabular-nav :guard="$routePrefix" :items="[
    [
        'title' => 'Summary Form',
        'route' => 'overtime.form',
        'routeParams' => ['view' => 'summary'],
    ],
    [
        'title' => 'Overtime Requests',
        'route' => 'overtime.form',
        'routeParams' => ['view' => 'requests'],
    ],
]" :isActiveClosure="function ($isActive, $item) use ($view) {
    return $isActive && $view === $item['routeParams']['view'];
}" />
