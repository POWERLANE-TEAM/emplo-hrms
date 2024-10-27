@php
    $nonce = csp_nonce();
@endphp

@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])

@section('head')
    <title>Home Page</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/hr/dashboard.js'])
@endPushOnce
@pushOnce('styles')
    @vite(['resources/css/employee/dashboard.css'])
@endPushOnce
@section('content')
    <x-breadcrumbs>
        <x-slot:breadcrumbs>
            <x-breadcrumb href="/">
                Home
            </x-breadcrumb>
            <x-breadcrumb href="employee/dashboard" :active="request()->is('employee/dashboard')">
                Dashboard
            </x-breadcrumb>
        </x-slot:breadcrumbs>
    </x-breadcrumbs>


    <x-layout.main-heading :isHeading="true">
        <x-slot:heading>
            Good afternoon, {{ $user->account->first_name }}!
        </x-slot:heading>

        <x-slot:description>
            <p>It is <time datetime="{{ now() }}"> {{ \Carbon\Carbon::now()->format('l, d F') }}</time></p>
        </x-slot:description>


    </x-layout.main-heading>

    <x-table.top-menu-layout>
        <span class="fw-bold fs-5">
            <span class=" text-primary">
                11
                <span>requirements</span>
            </span>
            <span>
                out of
                <span>
                    18
                </span>
                submitted
            </span>
        </span>

        <div class="ms-md-auto" wire:ignore>
            <button class=" bg-transparent border-0"><i class="icon p-1 d-inline text-info"
                    data-lucide="arrow-down-wide-narrow"></i>Sort By</button>
            <button class="bg-transparent border-0"><i class="icon p-1 d-inline text-info"
                    data-lucide="list-filter"></i>Filters</button>
        </div>
        <div class="dropdown px-2 d-inline-block" wire:ignore>
            <button class=" bg-transparent border-0 dropdown-toggle d-flex align-content-center" data-bs-toggle="dropdown">
                <i class="icon p-1 d-inline text-info" data-lucide="download"></i>
                Download as
            </button>
            <ul class="dropdown-menu">
                <li class="dropdown-item">PDF</li>

            </ul>
        </div>
    </x-table.top-menu-layout>

    {{-- <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th scope="col">Handle</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="table-active">Larry the Bird</td>
                <td class="table-active">Larry the Bird</td>
                <td class="table-active">Larry the Bird</td>
                <td class="table-active">Larry the Bird</td>
            </tr>
        </tbody>
    </table> --}}

    <table class="table table-hover table-borderless text-center position-relative" aria-label="Pre-employment documents"
        aria-description="List of Pre-employment documents to be submitted.">
        <thead>
            <tr>
                <th scope="col" class="col-5 text-wrap"><i class="icon p-1 d-inline text-primary"
                        data-lucide="file-text"></i>Requirement
                </th>
                <th scope="col" class="col-1  text-wrap"><i class="icon p-1 d-inline text-primary"
                        data-lucide="check-circle"></i>Status</th>
                <th scope="col" class="col-3  text-wrap"><i class="icon p-1 d-inline text-primary"
                        data-lucide="paperclip"></i>Attachment</th>
                <th scope="col" class="col-3  text-wrap"><i class="icon p-1 d-inline text-primary"
                        data-lucide="upload"></i>Upload
                </th>
            </tr>
        </thead>

        <tbody>
            <tr class="border-2 rounded-2 outline" style="height: 100px; vertical-align: middle;">
                <td class="">
                    <div class="fw-bold">Document</div>
                </td>
                <td>
                    <x-status-badge color="danger">Invalid</x-status-badge>
                </td>
                <td><button class="btn bg-transparent text-decoration-underline text-capitalize text-nowrap">View
                        Attachment</button></td>
                <td><button class="btn btn-primary">Upload</button></td>
            </tr>
        </tbody>
    </table>
@endsection
