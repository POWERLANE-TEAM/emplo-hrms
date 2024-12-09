@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Leaves</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/leaves.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/basic/leaves.css'])

@endPushOnce
@section('content')

<x-headings.header-link heading="{{ __('Leaves') }}"
    description="{{ __('Manage your leave balance and request leaves.') }}" label="Request Leave" nonce="{{ $nonce }}"
    href="{{ route($routePrefix . '.general.leaves.request') }}">
</x-headings.header-link>

<!-- SECTION: Leaves Info Cards + Dropdown -->
<div class="col-md-12 pt-3 leaves-info">
    <section class="row">
        <!-- Total Days -->
        <div class="col-md-3 mb-3">
            <div class="card bg-body-secondary border-0 py-4 px-5 h-100">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <p class="fs-3 fw-bold">20</p>
                    <p class="fs-5">Total Leaves Days</p>
                </div>
            </div>
        </div>

        <!-- SECTION: Leave Balance Card -->
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white border-0 py-4 px-5 h-100">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <p class="fs-3 fw-bold">6</p>
                    <p class="fs-5">Days Left</p>
                </div>
            </div>
        </div>

        <!-- SECTION: Next Payslips Card -->
        <div class="col-md-3 mb-3">
            <div class="card bg-body-secondary border-0 py-4 px-5 h-100">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <p class="fs-3 fw-bold">14</p>
                    <p class="fs-5">Days Taken</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3 d-flex justify-content-center align-items-center flex-column">
            <!-- BACK-END Replace: Year options. Default is current year. -->
            <x-form.boxed-dropdown id="priority" label="{{ __('Select Year') }}" :nonce="$nonce" :options="['2022' => '2022', '2023' => '2023', '2024' => '2024']" placeholder="Select year">
            </x-form.boxed-dropdown>
        </div>
    </section>
</div>

<!-- BACK-END REPLACE: Employee's leaves table. -->
@endsection