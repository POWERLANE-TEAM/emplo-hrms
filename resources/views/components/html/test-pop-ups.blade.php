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
    <x-pop-ups.alerts :type="'success'" :message="'This is a success alert!'" />

    <!-- Error Alert -->
    <x-pop-ups.alerts :type="'danger'" :message="'This is an error alert!'" />

    <!-- Warning Alert -->
    <x-pop-ups.alerts :type="'warning'" :message="'This is a warning alert!'" />

    <!-- Info Alert -->
    <x-pop-ups.alerts :type="'info'" :message="'This is an info alert!'" />

</div>

<div class="container mt-5">
    <h2 class="mb-4">Toasts</h2>

    <!-- Success Toast -->
    <button class="btn btn-success mt-3" onclick="showToast('success', 'This is a success toast!')">Show
        Success Toast</button>

    <!-- Error Toast -->
    <button class="btn btn-danger mt-3" onclick="showToast('danger', 'This is an error toast!')">Show
        Error Toast</button>

    <!-- Warning Toast -->
    <button class="btn btn-warning mt-3" onclick="showToast('warning', 'This is a warning toast!')">Show Warning
        Toast</button>

    <!-- Info Toast -->
    <button class="btn btn-info mt-3" onclick="showToast('info', 'This is an info toast!')">Show Info
        Toast</button>
</div>

<div class="container mt-5">
    <h2 class="mb-4">Modals</h2>

    <!-- Show Success Modal Button -->
    <button class="btn btn-success mt-3" onclick="openModal('success-modal')">Show Success Modal</button>

    <!-- Show Error Modal Button -->
    <button class="btn btn-danger mt-3" onclick="openModal('error-modal')">Show Error Modal</button>
</div>

<div class="container mt-5">
    <h2 class="mb-4">Confirmation Modals</h2>

    <!-- Show Confirm Delete Modal Button -->
    <button class="btn btn-danger mt-3" onclick="openModal('confirm-delete-modal')">Show Delete Confirmation Modal</button>

    <!-- Show Caution Modal Button -->
    <button class="btn btn-warning mt-3" onclick="openModal('caution-modal')">Show Caution Confirmation Modal</button>

</div>


<!-- Success Modal -->
<x-modals.status-modal type="success" label="Success Notification" header="Success title here" id="success-modal"
    message="Replace success action message here." />

<!-- Error Modal -->
<x-modals.status-modal type="error" label="Error notification" header="Error title here" id="error-modal"
    message="Replace error action message here." />

<x-modals.confirmation.confirm-modal type="delete" label="Confirm Delete modal" header="Destructive Action Title?" id="confirm-delete-modal"
    message="Are you sure you want to do this destructive action?" actionButtonTitle="Confirm Delete" />

<x-modals.confirmation.confirm-modal type="caution" label="Caution modal" header="Caution Modal Title?" id="caution-modal"
    message="Proceed with caution?" actionButtonTitle="Proceed" />
@endsection

<a href="/information-centre?section=privacy-policy">Go to System Policy</a>