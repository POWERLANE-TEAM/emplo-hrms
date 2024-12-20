@props([
    'no_crawl' => false,
    'description' => '',
    'main_cont_class' => '',
    'font_weights' => [' 400 ', '500', '700'],
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <style nonce="{{ $nonce }}">
        {!! Vite::content('resources/css/input/disable-submit.css') !!}
    </style>
    <x-html.meta />
    <x-html.meta-seo />

    @yield('head')

    <x-fav-icon />

    {{ Vite::useScriptTagAttributes(['onerror' => 'handleError(error)']) }}
    @php
        Debugbar::getJavascriptRenderer()->setCspNonce($nonce);
    @endphp

    {!! RecaptchaV3::initJs() !!}

    <x-global-debug />

    <!-- Fonts -->
    <x-fonts :font_weights="$font_weights" />

    <!-- Scripts -->
    <x-no-script-head />
    <x-authenticated-broadcast-id />
    <x-livewire-listener />

    @if (session('clearSessionStorageKeys'))
        <script nonce="{{ $nonce }}">
            @php
                $keys = session('clearSessionStorageKeys');
            @endphp

            @if (is_array($keys))
                @foreach ($keys as $key)
                    sessionStorage.removeItem('{{ $key }}');
                @endforeach
            @else
                sessionStorage.removeItem('{{ $keys }}');
            @endif
        </script>
    @endif

    @auth
        @vite(['resources/js/listeners/online-users.js'])
    @endauth

    {{--  Waiting for this fix in livewire https://github.com/livewire/livewire/pull/8793  --}}
    {{-- livewire.js?id=cc800bf4:9932 Detected multiple instances of Livewire running --}}
    {{-- livewire.js?id=cc800bf4:9932 Detected multiple instances of Alpine running --}}
    {{-- @livewireStyles(['nonce' => $nonce])
@livewireScripts(['nonce' => $nonce]) --}}
    @once
        @livewireStyles()
    @endonce

</head>

<body data-bs-theme="{{ session('themePreference', 'light') }}">
    @yield('critical-styles')
    <x-no-script-body />

    <!-- Styles -->
    @vite(['resources/css/style.css'])
    @stack('pre-styles')
    @stack('styles')

    @env('local')
    @vite(['node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'])
    @endenv

    @env('production')
    @vite(['node_modules/bootstrap/dist/js/bootstrap.bundle.min.js?commonjs-entry'])
    @endenv

    @stack('pre-scripts')

    @yield('before-nav')

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    @yield('header-nav')

    @yield('before-main')

    <main class="main {{ $main_cont_class }}">
        @yield('content')
    </main>

    @yield('after-main')

    @once
        @livewireScripts()
    @endonce

    @stack('scripts')

    @yield('footer')



    @if (session('verification-email-error'))
        @once
            <x-modals.email-verif-error :message="session('verification-email-error')" />
        @endonce
        <script nonce="{{ $nonce }}">
            document.addEventListener("livewire:initialized", () => {
                document.addEventListener("livewire:navigated", () => {
                    window.openModal('modal-verification-email-error');
                });
            });
        </script>
    @endif

    @if (session('verification-email-success'))
        @once
            <x-modals.email-sent label="Verification email sent" id="modal-verification-email-success" header="Email Sent"
                :message="session('verification-email-success')" />
        @endonce
        <script nonce="{{ $nonce }}">
            document.addEventListener("livewire:initialized", () => {
                window.openModal('modal-verification-email-success');
            });
        </script>

        @php
            Session::forget('verification-email-success');
        @endphp
    @endif

</body>

</html>
