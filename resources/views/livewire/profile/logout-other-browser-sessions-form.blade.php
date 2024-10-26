<section class="my-5">
    <div class="card mb-4">
        <div class="card-header">
            <div class="fs-5 fw-medium">{{ __('Browser Sessions') }}</div>
            <p class="small text-muted mb-0">{{ __('Manage and log out your active sessions on other browsers and devices.') }}</p>
        </div>
        <div class="card-body">
            <p class="text-muted">{{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}</p>
    
            @if (count($this->sessions) > 0)
                <div class="mt-4">

                    {{-- Other Browser Sessions --}}
                    @foreach ($this->sessions as $session)
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                @if ($session->agent->isDesktop())
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-md text-muted">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                @endif
                            </div>
    
                            <div class="ms-3">
                                <p class="mb-0 small text-muted">{{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }} - {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }}</p>
                                <p class="small text-muted">
                                    {{ $session->ip_address }},
                                    @if ($session->is_current_device)
                                        <span class="text-success fw-semibold">{{ __('This device') }}</span>
                                    @else
                                        {{ __('Last active') }} {{ $session->last_active }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
    
            <div class="d-flex align-items-center mt-4">
                <button type="button" class="btn btn-primary" wire:click="confirmLogout" wire:loading.attr="disabled">
                    {{ __('Log Out Other Browser Sessions') }}
                </button>
                <span class="ms-3 text-success" wire:target="loggedOut" wire:loading.remove>{{ __('Done.') }}</span>
            </div>
        </div>
    </div>
    
    {{-- Log Out Other Devices Confirmation Modal --}}
    <div class="modal {{ $this->confirmingLogout ? 'd-block' : 'fade' }}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250) tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="{{ ! $this->confirmingLogout }}">
        <div class="modal-dialog modal-lg" wire:model.live="confirmingLogout">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmLogoutModalLabel">{{ __('Log Out Other Browser Sessions') }}</h5>
                </div>
                <div class="modal-body">
                    <p>{{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}</p>
                    <div class="mt-3">
                        <input type="password" id="password" class="form-control mt-1" placeholder="{{ __('Password') }}" wire:model="password" wire:keydown.enter="logoutOtherBrowserSessions" />
                        @includeWhen($errors->first('password'), 'components.form.input-feedback', [
                            'feedback_id' => 'password',
                            'message' => $errors->first('password')])
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary ms-2" wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled">{{ __('Log Out Other Browser Sessions') }}</button>
                </div>
            </div>
        </div>
    </div>
    
</section>