@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('App\Models\Employee')

@section('head')
    <title> {{ $employee->full_name }} • Profile</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@pushOnce('scripts')
    <script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
    @filepondScripts
    <script nonce="{{ $nonce }}">
        document.addEventListener('livewire:init', () => {
            LivewireFilePond.registerPlugin(FilePondPluginPdfPreview);
        });
    </script>
    @vite(['resources/js/employee/hr-manager/employee-info.js'])
    @vite(['resources/js/employee/calendar.js'])
@endPushOnce

@pushOnce('styles')
    <link href="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css" rel="stylesheet">
    @vite(['resources/css/employee/hr-manager/employee-info.css'])
@endPushOnce

@section('content')
    <x-breadcrumbs>
        <x-slot:breadcrumbs>
            <x-breadcrumb :href="route($routePrefix . '.employees.masterlist.all')">
                {{ __('Employee Masterlist') }}
            </x-breadcrumb>
            <x-breadcrumb :active="request()->routeIs($routePrefix . '.employees.information')">
                {{ $employee->last_name . __('\'s Information') }}
            </x-breadcrumb>
        </x-slot:breadcrumbs>
    </x-breadcrumbs>

    <section class="row pt-2">
        <div class="col-md-4">
            <x-form.boxed-selectpicker id="incident_type" :nonce="$nonce" :options="Employee::all()->mapWithKeys(fn($item) => [$item->employee_id => $item->full_name])->toArray()"
                onchange="handleEmployeeChange(this.value)" placeholder="Select employee">
            </x-form.boxed-selectpicker>

            <script>
                const handleEmployeeChange = (employeeId) => {
                    if (employeeId) {
                        const url = `{{ route($routePrefix . '.employees.information', ['employee' => ':employeeId']) }}`.replace(
                            ':employeeId', employeeId);
                        window.location.href = url;
                    }
                }
            </script>
        </div>

        <div class="col-md-8 d-flex align-items-center" wire:ignore>
            @include('components.includes.tab_navs.employees-navs')
        </div>
    </section>

    <section class="mt-3">
        <div class="mt-1 px-3 py-3 w-100">

            <!-- Information Tab Section-->

            <!-- Sub-section: Employee Information -->
            <section id="information" class="tab-section-employee">
                <livewire:hr-manager.employees.information :$employee />
            </section>
            
            <!-- Attendance Tab Section -->
            <section id="attendance" class="tab-section-employee">
                <livewire:hr-manager.employees.attendance :$employee />
            </section>

            <!-- Payslips Tab Section -->
            <section id="payslips" class="tab-section-employee">
                <livewire:hr-manager.employees.payslips :$routePrefix :$employee />
            </section>

            <!-- Contract Tab Section -->
            <section id="contract" class="tab-section-employee">
                <livewire:hr-manager.employees.contract :$routePrefix :$employee />
            </section>

            <!-- Leaves Tab Section -->
            <section id="leaves" class="tab-section-employee">
                <livewire:hr-manager.employees.leaves :$routePrefix :$employee />
            </section>

            <!-- Overtime Tab Section -->
            <section id="overtime" class="tab-section-employee">
                <livewire:hr-manager.employees.overtime :$employee />
            </section>

            <!-- Payroll Summary Section -->
            <section id="psummary" class="tab-section-employee">
                <livewire:hr-manager.employees.payroll-summary :$employee />
            </section>

            <!-- Trainings Section -->
            <section id="trainings" class="tab-section-employee">
                <livewire:hr-manager.employees.trainings :$employee />
            </section>
        </div>
    </section>
@endsection
