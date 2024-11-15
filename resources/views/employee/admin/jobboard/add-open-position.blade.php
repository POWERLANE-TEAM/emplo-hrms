{{-- Initialization Section: Sets CSP nonce, retrieves authenticated user, --}}
@php
    $nonce = csp_nonce();
    $user = Auth::user();
@endphp


{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])


{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Add New Open Job Position</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@pushOnce('pre-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/hr-manager/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce
{{-- END OF Head Section: Title, Scripts, & Styles --}}



{{-- Body/Content Section --}}
@section('content')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        Update Job Board
    </x-slot:heading>

    <x-slot:description>
        Update the job board with new open positions on the recruitment platform
    </x-slot:description>
</x-headings.main-heading>


@livewire('info.show-job-info')

{{-- File Path: 
        app\Livewire\Info\ShowJobInfo.php
        resources\views\livewire\info\show-job-info.blade.php
--}}


<script>
    // For debugging purposes. Remove if no longer needed.
    document.addEventListener('DOMContentLoaded', function () {
        console.log("DOM fully loaded and parsed");

        document.getElementById('job_position').addEventListener('change', function () {
            console.log('Dropdown changed to:', this.value);
        });
    });
</script>

@endsection
