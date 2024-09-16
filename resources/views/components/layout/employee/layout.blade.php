@props([
    'no_crawl' => false,
    'description' => '',
    'main_cont_class' => 'mt-6',
    'font_weights' => [' 400 ', '500', '700'],
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @env('production')
    <style nonce="{{ $nonce }}">
        {!! Vite::content('resources/css/input/disable-submit.css') !!}
    </style>
    @endenv

    <!-- Preload style.css -->
    <link rel="preload" href="{{ Vite::asset('resources/css/style.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ Vite::asset('resources/css/style.css') }}">
    </noscript>

    <x-html.meta />
    <x-html.meta-seo />

    @yield('head')

    <x-fav-icon />
    @laravelPWA

    {{-- {{ Vite::useScriptTagAttributes(['onerror' => 'handleError(error)']) }}
    @php
    Debugbar::getJavascriptRenderer()->setCspNonce($nonce);
    @endphp --}}

    <x-global-debug />

    <!-- Fonts -->
    <x-fonts :font_weights="$font_weights" />

    <!-- Scripts -->
    <x-authenticated-broadcast-id />
    <x-livewire-listener />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    @if(session('clearSessionStorageKeys'))
    <script nonce="{{ $nonce }}">
        @php
            $keys = session('clearSessionStorageKeys');
        @endphp

        @if(is_array($keys))
            @foreach($keys as $key)
                sessionStorage.removeItem('{{ $key }}');
            @endforeach
        @else
            sessionStorage.removeItem('{{ $keys }}');
        @endif
    </script>
    @endif

    {{-- Waiting for this fix in livewire https://github.com/livewire/livewire/pull/8793 --}}
    {{-- livewire.js?id=cc800bf4:9932 Detected multiple instances of Livewire running --}}
    {{-- livewire.js?id=cc800bf4:9932 Detected multiple instances of Alpine running --}}
    {{-- @livewireStyles(['nonce' => $nonce])
    @livewireScripts(['nonce' => $nonce]) --}}
    @livewireStyles

    @vite([
        'resources/js/listeners/online-users.js',
        'resources/js/app.js',
        'resources/css/style.css'
    ])
</head>

<body class="employee-main" data-bs-theme="{{ session('themePreference', 'light') }}">
    @yield('critical-styles')
    <x-no-script-body />

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

    @if (!View::hasSection('header-nav'))
        <x-layout.employee.nav.main-menu class="position-sticky top-0 start-0" :user="$user" :userPhoto="$userPhoto"></x-layout.employee.nav.main-menu>
    @else
        @yield('header-nav')
    @endif

    @yield('before-main')

    <div class="main-layout-container">
        <main class="main {{ $main_cont_class }}">
            @yield('content')

            <!-- Toast Container -->
            <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>
        </main>
    </div>

    @yield('after-main')

    @yield('footer')

    @stack('scripts')

    @livewireScripts
</body>

</html>
