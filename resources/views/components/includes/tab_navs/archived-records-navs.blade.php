{{-- 
* |-------------------------------------------------------------------------- 
* | @include for Archive Employees 201 Records
* |-------------------------------------------------------------------------- 
--}}

<x-sub-navs.in-page-tab-nav :tabs="[
        ['title' => 'Information', 'section' => 'information'],
        ['title' => 'Attendance', 'section' => 'attendance'],
        ['title' => 'Payslips', 'section' => 'payslips'],
        ['title' => 'Contract', 'section' => 'contract'],
        ['title' => 'Leaves', 'section' => 'leaves'],
        ['title' => 'OT Summary', 'section' => 'overtime'],
        ['title' => 'Evaluations', 'section' => 'evaluations'],
        ['title' => 'Issues', 'section' => 'issues'],
]" />