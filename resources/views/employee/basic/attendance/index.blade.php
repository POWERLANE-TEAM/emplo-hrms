@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Attendance</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/attendance.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/basic/attendance.css'])
    @vite(['resources/js/employee/calendar.js'])

@endPushOnce
@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Attendance') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('Overview of your attendance, overtime and absences.') }}
    </x-slot:description>
</x-headings.main-heading>

<div x-data="{ 
    view: 'summary',
    period: '1',
    init() {
        this.updateView();
        this.dispatchPeriod();
    },
    updateView() {
        if (this.view === 'summary') {
            document.getElementById('summary-component').style.display = 'block';
            document.getElementById('logs-component').style.display = 'none';
        } else {
            document.getElementById('summary-component').style.display = 'none';
            document.getElementById('logs-component').style.display = 'block';
        }
    },
    dispatchPeriod() {
        Livewire.dispatch('periodSelected', this.period);  // Changed this line
    }
}">
    <div class="row">
        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <p class="mb-0 text-primary fw-bold me-3" style="min-width: 100px;">
                    Payroll Period:
                </p>
                <select x-model="period" @change="dispatchPeriod()" class="form-select" style="flex: 1;">
                    <option value="1">Sep 02, 2024 - Sep 27, 2024</option>
                    <option value="2">Oct 28, 2024 - Nov 27, 2024</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <p class="mb-0 text-primary fw-bold me-3">
                    View:
                </p>
                <select x-model="view" @change="updateView()" class="form-select" style="flex: 1;">
                    <option value="summary">Summary</option>
                    <option value="logs">Workday Logs</option>
                </select>
            </div>
        </div>
    </div>

    <div id="summary-component">
        <livewire:employee.attendance.summary />
    </div>
    <div id="logs-component" style="display: none;">
        <livewire:employee.attendance.workday-logs />
    </div>
</div>

@endsection