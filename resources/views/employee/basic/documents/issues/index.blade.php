@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Files | Issues</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/style.css'])
@endPushOnce

@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Issues Files') }}
    </x-slot:heading>

    <x-slot:description>
        <div class="mb-3">
            {{ __('View all files associated with your reported issues here.') }}         
        </div>
    </x-slot:description>
</x-headings.main-heading>

@include('components.includes.tab_navs.file-manager')

<livewire:employee.tables.my-issues-files-table :$routePrefix />

@endsection
