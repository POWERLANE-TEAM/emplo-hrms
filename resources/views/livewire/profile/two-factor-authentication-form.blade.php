<div class="row my-5">
    <div class="col-md-4">
        <section>
            @php
                $isActive = false; // BACK-END REPLACE: Current active state
                $statusText = $isActive ? 'Active' : 'Inactive';
                $statusColor = $isActive ? 'success' : 'danger';
            @endphp

            <div class="d-flex align-items-center">
                <p class="fs-3 fw-bold mb-0 me-3">Two-factor Authentication</p>
                <x-status-badge :color="$statusColor">{{ $statusText }}</x-status-badge>
            </div>

            <p class="mt-2">
                Add additional security to your account using two factor authentication.
            </p>
        </section>
    </div>

    <div class="col-md-8">
        <section>
            <div class="card px-4 pt-4 pb-4 border-0 bg-secondary-subtle">
                <h6 class="mb-0 text-lg font-medium text-gray-900">
                    @if ($this->enabled)
                        @if ($showingConfirmation)
                            {{ __('Finish enabling two factor authentication.') }}
                        @endif
                    @else
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <p class="mb-0">{{ __('Security Alert!') }}</p>
                            <hr>
                            <div class="small mb-0">
                                {{ __('We strongly recommend enabling two factor authentication for an additional layer of security to your account.') }}
                            </div>
                            <button type="button" class="btn-sm btn-close shadow-none" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                </h6>

                <div class="mt-0">

                    <p class="fs-4 fw-bold">You have not yet enabled Two-factor authentication.</p>
                    <p>
                        {{ __('When two-factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone’s Google Authenticator application.') }}
                    </p>
                </div>

                @if ($this->enabled)
                        @if ($showingQrCode)
                                <div>
                                    <div class="alert alert-info">
                                        <p class="fw-semibold">
                                            @if ($showingConfirmation)
                                                {{ __('To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.') }}
                                            @else
                                                {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="d-flex justify-content-start">
                                        {!! $this->user->twoFactorQrCodeSvg() !!}
                                    </div>

                                    <div class="mt-3">
                                        <strong>{{ __('Setup Key') }}:</strong> {{ decrypt($this->user->two_factor_secret) }}
                                    </div>
                                </div>

                                @if ($showingConfirmation)
                                        <div class="mt-2">
                                            <x-form.input-text label="{{ __('') }}" id="code" type="text" class="form-control" name="code"
                                                placeholder="{{ __('Enter the OTP code') }}" autofocus autocomplete="one-time-code" />
                                            @includeWhen($errors->first('code'), 'components.form.input-feedback', [
                                        'feedback_id' => 'code',
                                        'message' => $errors->first('code')
                                    ])
                                        </div>
                                @endif
                        @endif

                        @if ($showingRecoveryCodes)
                            <div class="alert alert-info">
                                <p>{{ __('Store these recovery codes securely. They can be used to recover access to your account if you lose your authentication device.') }}
                                </p>
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                                    <li class="list-group-item">{{ $code }}</li>
                                @endforeach
                            </ul>
                        @endif
                @endif

                <div class="mt-3">
                    @if (!$this->enabled)
                        <x-modals.confirms-password wire:then="enableTwoFactorAuthentication">
                            <button class="btn btn-primary py-2" type="button" wire:loading.attr="disabled">
                                {{ __('Enable Two-factor Authentication') }}
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
                                <button class="btn btn-primary mr-3" wire:loading.attr="disabled">
                                    {{ __('Confirm OTP') }}
                                </button>
                            </x-modals.confirms-password>
                        @else
                            <x-modals.confirms-password wire:then="showRecoveryCodes">
                                <button class="btn btn-primary mr-3">
                                    {{ __('Show Recovery Codes') }}
                                </button>
                            </x-modals.confirms-password>
                        @endif

                        @unless ($this->user->hasRole(\App\Enums\UserRole::ADVANCED))
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
        </section>
    </div>
</div>