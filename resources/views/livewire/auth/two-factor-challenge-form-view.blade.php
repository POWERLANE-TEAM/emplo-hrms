@extends('components.layout.employee.layout')

@section('head')
    <title>Two Factor Authentication</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/admin/login.js'])
@endPushOnce

@section('critical-styles')
    @vite(['resources/css/guest/secondary-bg.css'])
@endsection

@pushOnce('styles')
    @vite(['resources/css/login.css'])
@endPushOnce

@section('before-nav')
    <x-layout.employee.nav.secondary-bg />
@endsection

@section('header-nav')
    <x-layout.employee.nav.secondary-header />
@endsection

@section('content')
    @livewire('auth.two-factor-challenge-form')
@endsection

@section('footer')
    <x-guest.footer />
@endsection
