@extends('components.layout.app', ['description' => 'Hiring Page', 'font_weights' => ['900', '600']])

@section('head')
    <title>Toast Alerts</title>
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

<!-- Toast Container (Bootstrap's auto-managed container) -->
<div class="toast-container position-fixed top-0 end-0 p-3">

</div>

<div class="container mt-5">
    <h2 class="mb-4">Test Alerts</h2>

    <!-- Success Alert -->
    <x-alerts.toasts :type="'success'" :icon="'check-circle'" :message="'This is a success alert!'" />
    <button class="btn btn-success mt-3" onclick="showToast('success', 'This is a success toast!', 'check-circle')">Show Success Alert</button>

    <!-- Error Alert -->
    <x-alerts.toasts :type="'danger'" :icon="'alert-triangle'" :message="'This is an error alert!'" />
    <button class="btn btn-danger mt-3" onclick="showToast('danger', 'This is an error toast!!', 'alert-triangle')">Show Error Alert</button>

    <!-- Warning Alert -->
    <x-alerts.toasts :type="'warning'" :icon="'alert-octagon'" :message="'This is a warning alert!'" />
    <button class="btn btn-warning mt-3" onclick="showToast('warning', 'This is a warning toast!!', 'alert-octagon')">Show Warning Alert</button>

    <!-- Info Alert -->
    <x-alerts.toasts :type="'info'" :icon="'info'" :message="'This is an info alert!'" />
    <button class="btn btn-info mt-3" onclick="showToast('info', 'This is an info alert!', 'info')">Show Info Alert</button>
</div>
@endsection
