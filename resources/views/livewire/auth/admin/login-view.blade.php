@extends('components.layout.employee.layout')

@section('head')
<title>Sign in as Admin</title>
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

<style>
    {!! Vite::content('resources/css/guest/secondary-bg.css') !!}
</style>
@endsection

@pushOnce('styles')
    @vite(['resources/css/login.css'])
    @vite(['resources/css/animations/auth-effect.css'])
@endPushOnce

@section('before-nav')
<x-layout.employee.nav.secondary-bg />
@endsection

@section('header-nav')
<x-layout.employee.nav.secondary-header />
@endsection

@section('content')
@livewire('auth.admins.login')
@endsection


@section('footer')
<x-guest.footer />
@endsection