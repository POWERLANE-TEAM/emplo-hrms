@props([
    'no_crawl' => false,
    'description' => '',
    'main_cont_class' => 'mt-6',
    'font_weights' => [' 400 ', '500', '700'],
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <x-html.meta />
    <x-html.meta-seo />

    @yield('head')

    <x-fav-icon />

    {{ Vite::useScriptTagAttributes(['onerror' => 'handleError(error)']) }}
    @php
        Debugbar::getJavascriptRenderer()->setCspNonce($nonce);
    @endphp

    {{-- {!! RecaptchaV3::initJs() !!} --}}

    <x-global-debug />

    <!-- Fonts -->
    <x-fonts :font_weights="$font_weights" />

    <!-- Scripts -->
    <x-authenticated-broadcast-id />
    <x-livewire-listener />

    @vite(['resources/js/listeners/online-users.js'])

    {{-- Waiting for this fix in livewire https://github.com/livewire/livewire/pull/8793 --}}
    {{-- livewire.js?id=cc800bf4:9932 Detected multiple instances of Livewire running --}}
    {{-- livewire.js?id=cc800bf4:9932 Detected multiple instances of Alpine running --}}
    {{-- @livewireStyles(['nonce' => $nonce])
    @livewireScripts(['nonce' => $nonce]) --}}
    @once
        @livewireStyles()
    @endonce

</head>

<body class="employee-main">
    @yield('critical-styles')
    <x-no-script-body />

    <!-- Styles -->
    @vite(['resources/css/style.css'])
    @stack('pre-styles')
    @stack('styles')

    @if (!View::hasSection('bootstrap-script'))
        @vite(['node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'])
    @else
        @yield('bootstrap-script')
    @endif

    @stack('pre-scripts')


    @yield('before-nav')

    @if (!View::hasSection('header-nav'))
        <x-layout.employee.nav.main-menu class="position-sticky top-0 start-0" :user="$user" :userPhoto="$userPhoto"
            :defaultAvatar="$defaultAvatar"></x-layout.employee.nav.main-menu>
    @else
        @yield('header-nav')
    @endif

    @yield('before-main')

    <div class="main-layout-container">
        <main class="main {{ $main_cont_class }}">
            @yield('content')
        </main>
    </div>

    @yield('after-main')

    @once
        @livewireScripts()
    @endonce

    @yield('footer')

    @stack('scripts')
</body>

</html>