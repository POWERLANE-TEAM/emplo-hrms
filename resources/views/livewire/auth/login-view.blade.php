@extends('components.layout.app', ['description' => 'Guest Layout'])

@use('App\Http\Helpers\RoutePrefix')

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/login.js'])
@endPushOnce

@section('critical-styles')
    @use('Illuminate\Support\Facades\Vite')

    <style nonce="{{ $nonce }}">
        {!! Vite::content('resources/css/guest/secondary-bg.css') !!}
    </style>
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
    @php
        $componentName = 'auth.' . ($routePrefix ? $routePrefix . '.' : '') . 'login';
    @endphp
    @livewire($componentName)
@endsection


@section('footer')
    <x-guest.footer />
@endsection
