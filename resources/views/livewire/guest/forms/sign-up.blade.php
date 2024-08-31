@if (!empty($position))
    <?php $subtitle = 'Register to apply for ' . $position->title; ?>
@endif

@php
    $nonce = csp_nonce();
@endphp

<section nonce="{{ $nonce }}">
    <hgroup class="d-flex flex-column text-center">
        <header class="fs-3 text-primary">
            Sign Up
        </header>
        {{ $subtitle ?? '' }}
    </hgroup>

    <form wire:submit.prevent="create" action="applicant/sign-up" nonce="{{ $nonce }}">
        @csrf
        <label for="signUp-email">Email Address</label>
        <div class="input-group mb-3 position-relative">
            <div class="px-2 d-flex align-items-center position-absolute"><i data-lucide="mail"></i></div>
            <input type="email" aria-owns="signUp-email-feedback" name="email" wire:model="email"
                autocomplete="email"
                class="form-control  border-bottom ps-5
                @error('email')
                    is-invalid
                @enderror
                ">
            <div class="invalid-feedback" role="alert" id="signUp-email-feedback">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <label for="signUp-password">Password</label>
        <div class="input-group mb-3">
            <div class="px-2 d-flex position-absolute "><i data-lucide="lock"></i></div>
            <input type="password" id="signUp-password" aria-owns="signUp-password-feedback" name="password"
                wire:model="password" autocomplete="new-password"
                class="form-control rm-bg-icon border-bottom ps-5 z-0">
            <input type="checkbox"
                class="text-primary toggle-password position-absolute end-0 z-3
                @error('password')
                    is-invalid
                @enderror
            "
                aria-label="Show/Hide Password">
            <div class="invalid-feedback" role="alert" id="signUp-password-feedback">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="input-group mb-3 terms-condition">
            <input type="checkbox" id="terms-condition" name="consent" wire:model="consent"
                class="checkbox checkbox-primary">
            <label for="terms-condition" class="checkbox-label d-flex">I agree to
                the&#8194;<wbr>
                <span class="d-flex " role="list">
                    <a href="#" target="_blank" class="text-black" rel="noopener noreferrer"
                        role="listitem">Terms&nbsp;&&nbsp;Conditions
                    </a>
                    <span>&#8194;and&#8194;</span>
                    <a href="#" target="_blank" class="text-black"
                        rel="noopener noreferrer">Privacy&nbsp;Policy</a>
                </span>
            </label>
            <div class="invalid-feedback"></div>
        </div>

        <button type="submit" nonce="{{ $nonce }}" id="signUpBtn" class="btn btn-primary btn-lg" disabled>Sign
            Up</button>

    </form>
</section>
