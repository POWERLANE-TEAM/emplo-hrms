@extends('components.layout.employee.layout', ['description' => 'My Leave Attachments', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Files • Leaves</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/employee-info.css'])
@endPushOnce

@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Leaves Files') }}
    </x-slot:heading>

    <x-slot:description>
        <div class="mb-3">
            {{ __('View all files associated with your requested leaves here.') }}         
        </div>
    </x-slot:description>
</x-headings.main-heading>

@include('components.includes.tab_navs.file-manager')

<livewire:employee.tables.my-leaves-files-table :$routePrefix />

@endsection
