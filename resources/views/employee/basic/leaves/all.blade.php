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
    href="{{ route($routePrefix . '.leaves.create') }}">
</x-headings.header-link>

<div class="mb-3">
    <div class="fs-6">
        {{ __('Vacation Leave Credits: ') }}
        <span class="fw-semibold">
            {{ auth()->user()->account->silCredit->vacation_leave_credits }}
        </span>
    </div>
    <div class="fs-6">
        {{ __('Sick Leave Credits: ') }}
        <span class="fw-semibold">
            {{ auth()->user()->account->silCredit->sick_leave_credits }}
        </span>
    </div>
</div>

<div class="pb-2">
@include('components.includes.tab_navs.leaves.general-leaves-navs')
</div>

<livewire:employee.tables.my-leaves-table />

@endsection