@php
    $nonce = csp_nonce();
@endphp

@extends('layout.guest-01', ['description' => 'Guest Layout', 'nonce' => $nonce])

@section('head')
    <title>Login</title>
    <script src="build/assets/nbp.min.js" defer></script>
    @vite(['resources/js/login.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
@endsection


@section('content')
    @livewire('auth.login')
@endsection
