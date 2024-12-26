@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
    <title>Employees Attendance</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    {{--  --}}
@endPushOnce

@pushOnce('scripts')
    {{-- @vite(['resources/js/employee/.js']) --}}

    @rappasoftTableStyles

    @rappasoftTableThirdPartyStyles

    @rappasoftTableScripts

    @rappasoftTableThirdPartyScripts
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/attendance.css'])
@endPushOnce
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                Daily Time Record
            </x-slot:heading>

            <x-slot:description>
                <p><span class="rounded-circle bg-danger d-inline-block"></span><span
                        class=" fw-bold text-danger text-uppercase">Live</span>
                    viewing of today's employee attendance.</p>
            </x-slot:description>
        </x-headings.main-heading>

        <div>
            <div class="d-flex column-gap-2 column-gap-lg-3">
                <form class="d-contents">
                    <button class="btn btn-outline-primary h-100"><i class="icon icon-large mx-2"
                            data-lucide="arrow-left"></i>Previous Day</button>
                    <button class="btn btn-outline-primary h-100">Next Day<i class="icon icon-large mx-2"
                            data-lucide="arrow-right"></i></button>
                    <div class="border border-end-1"></div>
                    <button class="btn btn-primary"><i class="icon icon-large" data-lucide="calendar-search"></i></button>
                </form>

            </div>
        </div>
    </div>


    <livewire:tables.employees-attendance-table />
    {{-- <livewire:tables.employees-attendance-period-table /> --}}
    {{-- <livewire:tables.attendance-breakdown-table :employee="3"/> --}}
@endsection
