{{-- Extends layout --}}
@extends('components.layout.employee.layout', ['description' => 'User Accounts', 'nonce' => $nonce])

{{-- Head Section: Title, Scripts, & Styles --}}
@section('head')
    <title>Accounts</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
@vite(['resources/js/employee/hr-manager/dashboard.js'])
    @rappasoftTableStyles
    @rappasoftTableThirdPartyStyles
    @rappasoftTableScripts
    @rappasoftTableThirdPartyScripts
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/dashboard.css'])
@endPushOnce
{{-- END OF Head Section: Title, Scripts, & Styles --}}

{{-- Body/Content Section --}}
@section('content')
    <x-headings.main-heading :isHeading="true">
        <x-slot:heading>
            {{__('Accounts')}}
        </x-slot:heading>

        <x-slot:description>
            <p>{{ __('You can manage existing accounts here.') }}</p>
        </x-slot:description>
    </x-headings.main-heading>

    <livewire:admin.accounts-table />

@endsection
