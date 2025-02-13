@extends('components.layout.app', ['description' => 'Guest Layout', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
    <title>Create New Password</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/applicant/apply.js'])
@endPushOnce

@section('critical-styles')
    @vite('resources/css/guest/secondary-bg.css')
@endsection

@pushOnce('pre-styles')
@endPushOnce

@pushOnce('styles')
    <link href="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css" rel="stylesheet">
    @vite(['resources/css/forgot-password.css'])
@endPushOnce

@section('before-nav')
    <x-layout.guest.secondary-bg />
@endsection

@section('header-nav')
    <x-layout.guest.secondary-header />
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mt-4 mb-3 my-lg-5">

            <x-headings.main-heading :isHeading="true" class="text-primary fs-3 fw-bold mb-2 text-center">
                <x-slot:heading>
                    <p class="fs-1">Create a New Password</p>
                </x-slot:heading>

                <x-slot:description>
                    {{ __('Enter a strong password to secure your account. Make sure it meets the required criteria.') }}
                </x-slot:description>
            </x-headings.main-heading>

            <form method="POST" action="{{ route('password.reset') }}" >

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">{{ __('New Password') }}<span
                            class="text-danger">*</span></label>
                    <input type="password" id="password" class="form-control" autocomplete="new-password"
                        placeholder="Enter your new password...">
                    @error('password')
                        <div class="text-danger mt-1"></div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">{{ __('Confirm Password') }}<span
                            class="text-danger">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password"
                        placeholder="Re-enter your new password...">
                    @error('password_confirmation')
                        <div class="text-danger mt-1"></div>
                    @enderror
                </div>

                <div class="d-flex align-items-center">
                    <button type="submit" class="btn w-100 btn-primary mt-4" onclick="openModal('changed-successfully')">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- BACK-END Replace: Add this when changed successfully  -->
    <x-modals.status-modal type="success" label="Changed Successfully" header="Password changed successfully!"
        id="changed-successfully"
        message="Password has been changed. You can now <a href='{{ $routePrefix === 'admin' ? '/admin/login' : ($routePrefix === 'employee' ? '/employee/login' : '/login') }}'>sign in</a>." />
@endsection
