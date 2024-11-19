@extends('components.layout.app', ['description' => 'Guest Layout'])

@use('App\Http\Helpers\RoutePrefix')

@section('head')
    <title>Sign in {{ $routePrefix ? ' as ' . $routePrefix : '' }}</title>
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

@section('before-nav')
    <x-layout.{{ $routePrefix === 'admin' ? 'employee.secondary-bg' : 'guest.secondary-bg' }} />
@endsection

@section('header-nav')
    <x-layout.{{ $routePrefix === 'admin' ? 'employee.secondary-header' : 'guest.secondary-header' }} />
@endsection

@section('content')
    @php
        $componentName = 'auth.' . ($routePrefix ? $routePrefix . '.' : '') . 'login';
    @endphp
    @livewire($componentName)
@endsection


@section('footer')
    <x-guest.footer />
@endsection
