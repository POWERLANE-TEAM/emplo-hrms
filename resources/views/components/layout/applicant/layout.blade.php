@props(['icon_size' => '25', 'icon_ratio' => '1/1'])
@php
    $nonce = csp_nonce();
@endphp

@props([
    'no_crawl' => false,
    'description' => '',
    'main_cont_class' => 'container',
    'font_weights' => [' 400 ', '500', '700'],
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <x-html.meta />
    <x-html.meta-seo />

    @yield('head')

    <x-fav-icon />

    <?php Vite::useScriptTagAttributes(['onerror' => 'handleError(error)']); ?>
    @php
        Debugbar::getJavascriptRenderer()->setCspNonce($nonce);
    @endphp

    {!! RecaptchaV3::initJs() !!}

    <x-global-debug />

    <!-- Fonts -->
    <x-fonts :font_weights="$font_weights" />

    <!-- Scripts -->
    <x-authenticated-broadcast-id />
    <x-livewire-listener />

    @once
        @livewireStyles()
    @endonce

</head>

<body>
    @yield('critical-styles')
    <x-no-script-body />

    <!-- Styles -->
    @stack('pre-styles')
    @stack('styles')

    @if (!View::hasSection('bootstrap-script'))
        @vite(['vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'])
    @else
        @yield('bootstrap-script')
    @endif

    @stack('pre-scripts')
    @stack('scripts')

    @yield('before-nav')

    @if (!View::hasSection('header-nav'))
        <x-layout.applicant.header :sidebar_expanded="true" class="position-sticky top-0 start-0"></x-layout.applicant.header>
    @else
        @yield('header-nav')
    @endif

    @yield('before-main')

    <main class="mt-4 mt-md-5  {{ $main_cont_class }}">
        @yield('content')
    </main>

    @yield('after-main')

    <x-layout.applicant.footer />

    @once
        @livewireScripts()
    @endonce

    @yield('footer')
</body>

</html>
