{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Employee Information's tabular navigation bars
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.in-page-tab-nav tabClass="tab-link-employee" :tabs="[
        ['title' => 'Information', 'section' => 'information'],
        ['title' => 'Attendance', 'section' => 'attendance'],
        ['title' => 'Payslips', 'section' => 'payslips'],
        ['title' => 'Contract', 'section' => 'contract'],
        ['title' => 'Leave Balance', 'section' => 'leaves'],
        ['title' => 'Overtime', 'section' => 'overtime'],
]" />
