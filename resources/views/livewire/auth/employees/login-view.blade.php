@extends('components.layout.app', ['description' => 'Guest Layout'])

@section('head')
    <title>Sign in</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/login.js'])
    @vite(['resources/js/animations/auth-effect.js'])

@endPushOnce

@section('critical-styles')
    @use('Illuminate\Support\Facades\Vite')

    <style nonce="{{ $nonce }}">
        {!! Vite::content('resources/css/guest/secondary-bg.css') !!}
    </style>
@endsection

@pushOnce('styles')
    @vite(['resources/css/login.css'])
    @vite(['resources/css/animations/auth-effect.css'])
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