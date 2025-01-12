{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Performance Evaluation Result's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.in-page-tab-nav tabClass="tab-link-perf" :tabs="[
        ['title' => 'Overview', 'section' => 'overview'],
        ['title' => 'Comments', 'section' => 'comments']
]" />