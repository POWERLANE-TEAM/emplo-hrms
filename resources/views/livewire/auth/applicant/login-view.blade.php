@extends('components.layout.app', ['description' => 'Guest Layout'])

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/applicant/login.js'])
@endPushOnce

@section('critical-styles')
    @vite(['resources/css/guest/secondary-bg.css'])
@endsection

@pushOnce('styles')
    @vite(['resources/css/login.css'])
@endPushOnce

@section('before-nav')
    <x-layout.guest.secondary-bg />
@endsection

@section('header-nav')
    <x-layout.guest.secondary-header />
@endsection

@section('content')
    @livewire('auth.employees.login')
@endsection


@section('footer')
    <x-guest.footer />
@endsection
