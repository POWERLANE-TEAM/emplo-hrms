@php
    $nonce = csp_nonce();
@endphp

@extends('components.layout.app', ['description' => 'Guest Layout'])

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/login.js'])
@endPushOnce
@pushOnce('styles')
    @vite(['resources/css/login.css'])
@endPushOnce

@section('before-nav')
    <x-layout.guest-secondary-bg />
@endsection

@section('header-nav')
    <x-layout.guest-secondary-header />
@endsection

@section('content')
    @livewire('auth.applicants.login')
@endsection


@section('footer')
    <x-guest.footer />
@endsection
