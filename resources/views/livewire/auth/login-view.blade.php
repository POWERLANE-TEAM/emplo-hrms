@php

    if (session('status') == 'Your password has been reset.') {
        
        $previousUrl = url()->previous();
        $previousUrlPath = parse_url($previousUrl, PHP_URL_PATH);

        if ($previousUrlPath != route('login', [], false)) {
            session()->keep(['status']);
            $previousUrlQuery = parse_url($previousUrl, PHP_URL_QUERY);
            parse_str($previousUrlQuery, $queryParams);

            $previousRoutePrefix = $queryParams['redirectPrefix'] ?? '';

            if (!empty($previousRoutePrefix)) {
                $previousRoutePrefix .= '.';
            }

            $redirectRoute = route($previousRoutePrefix . 'login');
        }
    }
@endphp

@if (session('status') == 'Your password has been reset.' && isset($redirectRoute))
    <script>
        const redirectRoute = "{{ $redirectRoute }}";
        window.location.href = redirectRoute;
    </script>
@endif

@php
    $routePrefix = $routePrefix ?? '';
    $component = 'auth.' . ($routePrefix ? $routePrefix . '.' : '') . 'login';

    $loginMessage = '';
    if (session('status') || session('error') || $errors->has('credentials')) {
        $type = session('status') ? 'success' : 'danger';
        $message = session('status')
            ? session('status')
            : (session('error')
                ? session('error')
                : $errors->first('credentials'));
        $loginMessage = "<div class=\"alert alert-{$type} alert-dismissible fade show\">
            <span>{$message} </span>
            <button type=\"button\" class=\"btn-sm btn-close shadow-none\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
            </div>";
    }
@endphp

@extends('components.layout.app', ['description' => 'Guest Layout'])

@use('App\Http\Helpers\RouteHelper')

@section('head')
    <title>Sign in {{ $routePrefix ? ' as ' . ucfirst($routePrefix) : '' }}</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/login.js'])
    @vite(['resources/js/animations/auth-effect.js'])
    @vite(['resources/js/forgot-password.js'])
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
@endPushOnce

@section('critical-styles')
    @vite('resources/css/guest/secondary-bg.css')
@endsection

@pushOnce('styles')
    @vite(['resources/css/login.css', 'resources/css/animations/auth-effect.css'])
@endPushOnce

@section('before-nav')
    @if ($routePrefix === 'admin')
        <x-layout.employee.nav.secondary-bg />
    @else
        <x-layout.guest.secondary-bg />
    @endif
@endsection

@section('header-nav')
    @if ($routePrefix === 'guest')
        <x-layout.guest.secondary-header />
    @else
        <x-layout.employee.nav.secondary-header />
    @endif
@endsection

@section('content')
    @livewire($component, ['loginMessage' => $loginMessage])
@endsection

@section('footer')
    <x-guest.footer />
@endsection
