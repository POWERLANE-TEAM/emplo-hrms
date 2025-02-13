<section nonce="{{ $nonce }}" class="auth-form px-md-4 py-md-3 mx-auto hidden-until-load">

    <livewire:dialogues.forgot-password />

    <hgroup class="d-flex flex-column text-center mb-3 mt-md-n4">
        <header class="typewriter-text display-5 fw-semibold text-primary mb-3 d-none d-md-block">
            Welcome Back!
        </header>
        <span class="fadein-text fs-5 text-wrap">
            Enter your credentials to access your account.
        </span>
    </hgroup>

    <form action="login" method="POST" nonce="{{ $nonce }}" class="fadein-text">
        @csrf

        @if ($errors->has('credentials'))
            <div class="alert alert-danger bg-danger-subtle" role="alert">
                {{ $errors->first('credentials') }}
            </div>
        @else
            <div class="alert alert-danger opacity-0" role="alert" aria-hidden="true">
                Blank
            </div>
        @endif

        <x-form.email id="userLogin-email" label="Email Address" name="email" autocomplete="email" :nonce="$nonce"
            aria-owns="userLogin-email-feedback" class=" {{ $errors->has('email') ? 'is-invalid' : '' }}">
            <x-slot:input_icon_left>
                <i data-lucide="mail" class="icon-large"></i>
            </x-slot:input_icon_left>

            <x-slot:feedback>
                @include('components.form.input-feedback', [
                    'feedback_id' => 'userLogin-email-feedback',
                    'message' => $errors->first('email'),
                ])
            </x-slot:feedback>
        </x-form.email>

        <x-form.password id="userLogin-password" label="Password" name="password" autocomplete="current-password"
            :nonce="$nonce" aria-owns="userLogin-password-feedback"
            class=" {{ $errors->has('password') ? 'is-invalid' : '' }}">

            <x-slot:input_icon_left>
                <i data-lucide="lock" class="icon-large"></i>
            </x-slot:input_icon_left>

            <x-slot:toggle_password>
                @include('components.form.toggle-password', [
                    'toggler_id' => 'toggle-psw',
                    'controls' => 'userLogin-password',
                ])
            </x-slot:toggle_password>

            <x-slot:feedback>
                @include('components.form.input-feedback', [
                    'feedback_id' => 'userLogin-password-feedback',
                    'message' => $errors->first('password'),
                ])
            </x-slot:feedback>
        </x-form.password>



        <div class="d-flex flex-wrap gap-4 gap-md-5">
            <x-form.checkbox container_class="col-12 col-md-5 order-0" :nonce="$nonce" id="remember-toggle"
                name="remember" wire:model="remember" class="checkbox checkbox-primary">

                <x-slot:label>
                    Remember me
                </x-slot:label>
            </x-form.checkbox>

            <div class=" col-md-auto mx-auto me-md-0 ms-md-auto order-2 order-md-1">
                <button type="button" class="border-0 bg-transparent text-decoration-underline green-hover"
                    onclick="openModal('forgotPasswordModal')">
                    Forgot your password?
                </button>
            </div>

            <button type="submit" nonce="{{ $nonce }}" id="userLoginBtn"
                class="btn btn-primary btn-lg col-12 order-1 order-md-2">
                Sign In
            </button>
        </div>
    </form>
</section>
