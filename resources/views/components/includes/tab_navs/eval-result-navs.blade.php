{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Performance Evaluation Result's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.in-page-tab-nav :tabs="[
        ['title' => 'Overview', 'section' => 'overview'],
        ['title' => 'Da Records', 'section' => 'da-records'],
        ['title' => 'Attendance', 'section' => 'attendance'],
        ['title' => 'Comments', 'section' => 'comments']
]" />