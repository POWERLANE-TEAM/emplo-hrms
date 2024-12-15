@extends('components.layout.app', ['description' => 'Hiring Page', 'font_weights' => ['900', '600']])

@section('head')
<title>Pop Ups</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/hiring.js'])
@endPushOnce
@pushOnce('styles')
    @vite(['resources/css/hiring.css'])
@endPushOnce

@section('before-nav')

<div class="container mt-5">
    <h2 class="mb-4">Alerts</h2>

    <!-- Success Alert -->
    <x-pop-ups.alerts :type="'success'" :icon="'check-circle'" :message="'This is a success alert!'" />

    <!-- Error Alert -->
    <x-pop-ups.alerts :type="'danger'" :icon="'alert-triangle'" :message="'This is an error alert!'" />

    <!-- Warning Alert -->
    <x-pop-ups.alerts :type="'warning'" :icon="'alert-octagon'" :message="'This is a warning alert!'" />

    <!-- Info Alert -->
    <x-pop-ups.alerts :type="'info'" :icon="'info'" :message="'This is an info alert!'" />
</div>

<div class="container mt-5">
    <h2 class="mb-4">Toasts</h2>

    <!-- Success Toast -->
    <button class="btn btn-success mt-3" onclick="showToast('success', 'This is a success toast!')">Show
        Success Alert</button>

    <!-- Error Toast -->
    <button class="btn btn-danger mt-3" onclick="showToast('danger', 'This is an error toast!')">Show
        Error Alert</button>

    <!-- Warning Toast -->
    <button class="btn btn-warning mt-3"
        onclick="showToast('warning', 'This is a warning toast!')">Show Warning Alert</button>

    <!-- Info Toast -->
    <button class="btn btn-info mt-3" onclick="showToast('info', 'This is an info toast!')">Show Info
        Alert</button>
</div>

<div class="container mt-5">
    <h2 class="mb-4">Alerts</h2>

    <!-- Success Alert -->
    <x-pop-ups.alerts :type="'success'" :icon="'check-circle'" :message="'This is a success alert!'" />

    <!-- Error Alert -->
    <x-pop-ups.alerts :type="'danger'" :icon="'alert-triangle'" :message="'This is an error alert!'" />

    <!-- Warning Alert -->
    <x-pop-ups.alerts :type="'warning'" :icon="'alert-octagon'" :message="'This is a warning alert!'" />

    <!-- Info Alert -->
    <x-pop-ups.alerts :type="'info'" :icon="'info'" :message="'This is an info alert!'" />
</div>

<div class="container mt-5">
    <h2 class="mb-4">Modals</h2>

    <!-- Success Modal -->
    <button class="btn btn-success mt-3" onclick="openModal('register-email-alert')">Show
        Success Alert</button>

    <!-- Error Modal -->
    <button class="btn btn-danger mt-3" onclick="showToast('danger', 'This is an error toast!', 'alert-triangle')">Show
        Error Alert</button>

    <!-- Warning Modal -->
    <button class="btn btn-warning mt-3"
        onclick="showToast('warning', 'This is a warning toast!', 'alert-octagon')">Show Warning Alert</button>

    <!-- Info Modal -->
    <button class="btn btn-info mt-3" onclick="showToast('info', 'This is an info alert!', 'info')">Show Info
        Alert</button>
</div>


<x-modals.status-modal label="Registered email notification" id="register-email-alert"
    message="Please check your inbox for the next steps." />

@endsection