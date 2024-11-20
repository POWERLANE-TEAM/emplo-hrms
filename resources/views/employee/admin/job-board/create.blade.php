{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Create job listing', 'nonce' => $nonce])

{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Add New Open Job Position</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
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
        {{ __('Update Job Board') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('Update the job board with new open positions on the recruitment platform.') }}
    </x-slot:description>
</x-headings.main-heading>

<livewire:admin.job-board.create-job-listing />

@endsection
