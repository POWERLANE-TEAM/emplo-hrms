@php
    $routePrefix = $routePrefix ?? '';
    $component = 'auth.'.($routePrefix ? $routePrefix . '.' : '').'login';
@endphp

@extends('components.layout.app', ['description' => 'Guest Layout'])

@use('App\Http\Helpers\RoutePrefix')

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
@endPushOnce

@section('critical-styles')
    @vite('resources/css/guest/secondary-bg.css')
@endsection

@pushOnce('styles')
    @vite([
        'resources/css/login.css',
        'resources/css/animations/auth-effect.css'
    ])
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
    @livewire($component)
@endsection

@section('footer')
<x-guest.footer />
@endsection