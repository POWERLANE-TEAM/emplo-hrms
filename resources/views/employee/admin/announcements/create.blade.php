{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])

{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Create Announcement</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endPushOnce

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
        {{ __('Post Announcement') }}
    </x-slot:heading>

    <x-slot:description>
        {{ __('Post a new announcement to the announcements board.') }}
    </x-slot:description>
</x-headings.main-heading>

<livewire:admin.announcement.create-announcement />
@endsection

