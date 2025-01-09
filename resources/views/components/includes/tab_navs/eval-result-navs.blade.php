{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Performance Evaluation Result's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.in-page-tab-nav :tabs="[
        ['title' => 'Overview', 'section' => 'overview'],
        ['title' => 'Comments', 'section' => 'comments']
]" />