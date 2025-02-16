@extends('components.layout.app', ['description' => 'Guest Layout', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
    <title>Create New Password</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
@endPushOnce

@section('critical-styles')
    @vite('resources/css/guest/secondary-bg.css')
@endsection

@pushOnce('pre-styles')
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/forgot-password.css'])
    <style>
        input[type="email"] {
            pointer-events: none;
            -moz-pointer-events: none;
            -webkit-pointer-events: none;
        }

        [for="reset-pw-email"] {
            display: none;
        }
    </style>
@endPushOnce

@section('before-nav')
    <x-layout.guest.secondary-bg />
@endsection

@section('header-nav')
    <x-layout.guest.secondary-header />
@endsection

@section('content')
    <div class="d-flex justify-content-center  ">
        <div class="mt-4 mb-3 my-lg-5 col-10 col-md-8 col-lg-6 col-xxl-5">

            <x-headings.main-heading :isHeading="true" class="text-primary fs-3 fw-bold mb-2 text-center">
                <x-slot:heading>
                    <p class="fs-1">Create a New Password</p>
                </x-slot:heading>

                <x-slot:description>
                    {{ __('Enter a strong password to secure your account. Make sure it meets the required criteria.') }}
                </x-slot:description>
            </x-headings.main-heading>

            <script>
                const RESET_PASSWORD_ACTION = "{{ route('password.update') }}";
            </script>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <x-form.boxed-email id="reset-pw-email" label="Email Address" name="email" autocomplete="email" type="hidden" readonly disabled
                    :nonce="$nonce" class=" {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $request->email }}"
                    required>
                    <x-slot:input_icon_left>
                        <i data-lucide="mail" class="icon-large"></i>
                    </x-slot:input_icon_left>

                    <x-slot:feedback>
                        @include('components.form.input-feedback', [
                            'feedback_id' => 'reset-pw-email-feedback',
                            'message' => $errors->first('email'),
                        ])
                    </x-slot:feedback>
                    </x-form.email>

                    <x-form.boxed-password id="reset-password" label="Password" :has_confirm="true" name="password"
                        autocomplete="new-password" placeholder="Enter your new password&hellip;" :nonce="$nonce"
                        class=" {{ $errors->has('password') ? 'is-invalid' : '' }}">

                        <x-slot:toggle_password>
                            @include('components.form.toggle-password', [
                                'toggler_id' => 'tgl-reset-password',
                                'controls' => 'reset-password',
                            ])
                        </x-slot:toggle_password>

                        <x-slot:feedback>
                            @include('components.form.input-feedback', [
                                'feedback_id' => 'reset-password-feedback',
                                'message' => $errors->first('password'),
                            ])
                        </x-slot:feedback>
                    </x-form.boxed-password>

                    <x-form.boxed-password id="reset-password-confirm" label="Confirm Password" name="password_confirmation"
                        autocomplete="new-password" placeholder="Re-enter your new password&hellip;" :nonce="$nonce"
                        class=" {{ $errors->has('password') ? 'is-invalid' : '' }}">

                        <x-slot:toggle_password>
                            @include('components.form.toggle-password', [
                                'toggler_id' => 'tgl-reset-password-confirm',
                                'controls' => 'reset-password-confirm',
                                'label' => 'Show/Hide Password Confirmation',
                            ])
                        </x-slot:toggle_password>

                        <x-slot:feedback>
                            @include('components.form.input-feedback', [
                                'feedback_id' => 'reset-password-confirm-feedback',
                                'message' => $errors->first('password'),
                            ])
                        </x-slot:feedback>
                    </x-form.boxed-password>

                    @if (session('status'))
                        <script>
                            openModal('changed-successfully')
                        </script>
                    @endif

                    <div class="d-flex align-items-center">
                        <button type="submit" id="submitResetPassword" class="btn w-100 btn-primary mt-4">
                            <span wire:loading.class="d-none">{{ __('Save Changes') }}</span>
                            <span role="status" wire:loading>Saving...</span>
                            <span class="spinner-border spinner-border-sm text-light" aria-hidden="true"
                                wire:loading></span>
                        </button>
                    </div>
            </form>
        </div>
    </div>

    <!-- BACK-END Replace: Add this when changed successfully  -->
    <x-modals.status-modal type="success" label="Changed Successfully" header="Password changed successfully!"
        id="changed-successfully"
        message="Password has been changed. You can now <a href='{{ route($request->redirectPrefix ? $request->redirectPrefix . '.login' : 'login') }}'>sign in</a>." />

    @vite(['resources/js/reset-password.js'])
@endsection
