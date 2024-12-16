{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Notifications' tabular navs
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.in-page-tab-nav :tabs="[
        ['title' => 'General', 'section' => 'general'],
        ['title' => 'Urgent', 'section' => 'urgent']
]" />