{{-- Initialization Section: Sets CSP nonce, retrieves authenticated user, --}}
@php
    $nonce = csp_nonce();
    $user = Auth::user();
@endphp


{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])


{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
<title>Create Announcement</title>
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
        Post Announcement
    </x-slot:heading>

    <x-slot:description>
        Post a new announcement to the announcements board.
    </x-slot:description>
</x-headings.main-heading>


{{--
|--------------------------------------------------------------------------
| Announcement Form
|--------------------------------------------------------------------------
--}}

<section class="mx-2">
    <form>
        {{-- Input field for: Announcement Title --}}
        <x-form.boxed-input-text id="announcement_title" label="Announcement Title" name="announcement_title"
            :nonce="$nonce" :required="true">
        </x-form.boxed-input-text>

        {{-- Textarea field for: Description --}}
        <x-form.boxed-textarea id="announcement_desc" label="Description" name="announcement_desc" :nonce="$nonce"
            :rows="6" :required="true" />

        {{-- Submit Button --}}
        <x-buttons.main-btn id="post_announcement" label="Post Announcement" name="post_announcement" :nonce="$nonce"
            :disabled="false" class="w-25" :loading="'Posting...'" />
    </form>
</section>
@endsection



{{--
|--------------------------------------------------------------------------
| List of IDs and Names
| *Both ID and Name attributes share the same naming convention.
|--------------------------------------------------------------------------
| Element | ID/Name | Description
|--------------------------------------------------------------------------
| Announcement Title | announcement_title | Title of the announcement
| Description | announcement_desc | Main description field
| Post Announcement | post_announcement | Button to post announcement
|--------------------------------------------------------------------------
--}}