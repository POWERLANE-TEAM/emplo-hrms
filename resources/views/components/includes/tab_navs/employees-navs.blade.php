{{--
* |--------------------------------------------------------------------------
* | @include for Employee Information's tabular navigation bars
* |--------------------------------------------------------------------------
--}}

<x-sub-navs.in-page-tab-nav tabClass="tab-link-employee" :tabs="[
        ['title' => 'Information', 'section' => 'information'],
        ['title' => 'Attendances', 'section' => 'attendance'],
        ['title' => 'Payslips', 'section' => 'payslips'],
        ['title' => 'Contracts', 'section' => 'contract'],
        ['title' => 'Leaves', 'section' => 'leaves'],
        ['title' => 'Overtimes', 'section' => 'overtime'],
        ['title' => 'Payrolls', 'section' => 'psummary'],
        ['title' => 'Trainings', 'section' => 'trainings'],
]" />
