@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Payslips</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/style.css'])

@endPushOnce
@section('content')

<div class="row pt-2">
    <div class="col-md-8">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                {{ __('Payslips') }}
            </x-slot:heading>

            <x-slot:description>
                {{ __('Access and review all your payslips conveniently in one place.') }}
            </x-slot:description>
        </x-headings.main-heading>
    </div>

    <div class="col-md-4 d-flex justify-content-end align-items-center">
        <div class="card bg-primary-subtle border-0 py-4 px-5">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <p class="mb-0 fs-3 fw-bold fs-6">📌  Next Pay Date:                 </p>
                <p class="mb-0 fs-6">September 25, 2024</p>
            </div>
        </div>
    </div>
</div>

<!-- BACK-END REPLACE: Payslips table -->

@endsection