{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'Create User Account', 'nonce' => $nonce])

{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
    <title>Create Account</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce
{{-- END OF Head Section: Title, Scripts, & Styles --}}


{{-- Body/Content Section --}}
@section('content')

<x-headings.header-link heading="{{ __('Create an account') }}" description="{{ __('Kindly fill up the following information.') }}"
    label="Bulk Creation" nonce="{{ $nonce }}" href="{{ route($routePrefix.'.accounts') }}" />

<livewire:admin.accounts.create-account-form />

@endsection
