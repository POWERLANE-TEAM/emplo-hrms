@use('App\Enums\UserRole')

<section class="my-5">
    <div class="card">
        <div class="card-header">
            <div class="fs-5 fw-medium">{{ __('Two Factor Authentication') }}</div>
            <p class="small text-muted mb-0">{{ __('Add additional security to your account using two factor authentication.') }}</p>
        </div>

        <div class="card-body">
            <h6 class="text-lg font-medium text-gray-900">
                @if ($this->enabled)
                    @if ($showingConfirmation)
                        {{ __('Finish enabling two factor authentication.') }}
                    @else
                        {{ __('You have enabled two factor authentication.') }}
                    @endif
                @else
                    {{ __('You have not enabled two factor authentication.') }}
                @endif
            </h6>

            <div class="mt-3">
                <p class="max-w-xl text-md text-gray-600">
                    {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication.') }}
                </p>                
            </div>

            @if ($this->enabled)
                @if ($showingQrCode)
                    <div class="mt-4">
                        <div class="alert alert-info">
                            <p class="fw-semibold">
                                @if ($showingConfirmation)
                                    {{ __('To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.') }}
                                @else
                                    {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                                @endif
                            </p>
                        </div>

                        <div class="d-flex justify-content-center">
                            {!! $this->user->twoFactorQrCodeSvg() !!}
                        </div>
                        
                        <div class="mt-3">
                            <strong>{{ __('Setup Key') }}:</strong> {{ decrypt($this->user->two_factor_secret) }}
                        </div>
                    </div>

                    @if ($showingConfirmation)
                        <div class="mt-2">
                            <x-form.input-text label="{{ __('') }}" id="code" type="text" class="form-control" name="code" placeholder="{{ __('Enter the OTP code') }}" autofocus autocomplete="one-time-code" />
                            @includeWhen($errors->first('code'), 'components.form.input-feedback', [
                                'feedback_id' => 'code',
                                'message' => $errors->first('code')])          
                        </div>
                    @endif
                @endif

                @if ($showingRecoveryCodes)
                    <div class="alert alert-warning mt-4">
                        <p>{{ __('Store these recovery codes securely. They can be used to recover access to your account if you lose your authentication device.') }}</p>
                        <ul class="list-group">
                            @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                                <li class="list-group-item">{{ $code }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endif

            <div class="mt-5">
                @if (! $this->enabled)
                    <x-modals.confirms-password wire:then="enableTwoFactorAuthentication">
                        <button class="btn btn-primary" type="button" wire:loading.attr="disabled">
                            {{ __('Enable Two-Factor Authentication') }}
                        </button>
                    </x-modals.confirms-password>
                @else
                    @if ($showingRecoveryCodes)
                        <x-modals.confirms-password wire:then="regenerateRecoveryCodes">
                            <button class="btn btn-secondary mr-3">
                                {{ __('Regenerate Recovery Codes') }}
                            </button>
                        </x-modals.confirms-password>
                    @elseif ($showingConfirmation)
                        <x-modals.confirms-password wire:then="confirmTwoFactorAuthentication">
                            <button class="btn btn-success mr-3" wire:loading.attr="disabled">
                                {{ __('Confirm') }}
                            </button>
                        </x-modals.confirms-password>
                    @else
                        <x-modals.confirms-password wire:then="showRecoveryCodes">
                            <button class="btn btn-secondary mr-3">
                                {{ __('Show Recovery Codes') }}
                            </button>
                        </x-modals.confirms-password>
                    @endif

                    @unless ($this->user->hasRole(UserRole::ADVANCED))
                        @if ($showingConfirmation)
                            <x-modals.confirms-password wire:then="disableTwoFactorAuthentication">
                                <button class="btn btn-danger" wire:loading.attr="disabled">
                                    {{ __('Cancel') }}
                                </button>
                            </x-modals.confirms-password>
                        @else
                            <x-modals.confirms-password wire:then="disableTwoFactorAuthentication">
                                <button class="btn btn-danger" wire:loading.attr="disabled">
                                    {{ __('Disable Two-Factor Authentication') }}
                                </button>
                            </x-modals.confirms-password>                                            
                        @endif                        
                    @endunless

                @endif
            </div>
        </div>        
    </div>
</section>