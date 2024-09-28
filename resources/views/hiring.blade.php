@php
    $nonce = csp_nonce();
@endphp

@extends('components.layout.app', ['description' => 'Hiring Page', 'font_weights' => ['900', '600']])

@section('head')
    <title>Powerlane | Job Listings</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/hiring.js'])
@endPushOnce
@pushOnce('styles')
    @vite(['resources/css/hiring.css'])
@endPushOnce

@section('critical-styles')
    <x-layout.guest-primary-style />
@endsection

@section('before-nav')
    <section class="top-vector">
        <picture>
            <source media="(min-width:2560px)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-xl.webp') }}">

            {{--
            * |--------------------------------------------------------------------------
            * | START Higher or retina display  for 1200px breakpoint
            * |--------------------------------------------------------------------------
            --}}
            <source media="(min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-lg-2x.webp') }}">
            <source media="(min-width: 1200px) and (min--moz-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-lg-2x.webp') }}">
            <source media="(min-width: 1200px) and (-min-moz-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-lg-2x.webp') }}">
            <source media="(min-width: 1200px) and (-o-min-device-pixel-ratio: 2/1)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-lg-2x.webp') }}">
            <source media="(min-width: 1200px) and (min-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-lg-2x.webp') }}">

            {{--
            * |--------------------------------------------------------------------------
            * | END Higher or retina display  for 1200px breakpoint
            * |--------------------------------------------------------------------------
            --}}
            <source media="(min-width:1200px)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-lg.webp') }}">

            {{--
            * |--------------------------------------------------------------------------
            * | START Higher or retina display  for 576px breakpoint
            * |--------------------------------------------------------------------------
            --}}
            <source media="(min-width:768px)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.webp') }}">
            <source media="(min-width: 576px) and (-webkit-min-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.webp') }}">
            <source media="(min-width: 576px) and (min--moz-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.webp') }}">
            <source media="(min-width: 576px) and (-min-moz-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.webp') }}">
            <source media="(min-width: 576px) and (-o-min-device-pixel-ratio: 2/1)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.webp') }}">
            <source media="(min-width: 576px) and (min-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.webp') }}">

            {{--
            * |--------------------------------------------------------------------------
            * | END Higher or retina display  for 576px breakpoint
            * |--------------------------------------------------------------------------
            --}}

            <source media="(min-width:576px)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-sm.webp') }}">

            {{--
            * |--------------------------------------------------------------------------
            * | START Higher or retina display  for 320px breakpoint
            * |--------------------------------------------------------------------------
            --}}
            <source media="(min-width: 320px) and (-webkit-min-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-xs-2x.webp') }}">
            <source media="(min-width: 320px) and (min--moz-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-xs-2x.webp') }}">
            <source media="(min-width: 320px) and (-min-moz-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-xs-2x.webp') }}">
            <source media="(min-width: 320px) and (-o-min-device-pixel-ratio: 2/1)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-xs-2x.webp') }}">
            <source media="(min-width: 320px) and (min-device-pixel-ratio: 2)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-xs-2x.webp') }}">

            {{--
            * |--------------------------------------------------------------------------
            * | END Higher or retina display  for 320px breakpoint
            * |--------------------------------------------------------------------------
            --}}
            <source media="(min-width:320px)"
                srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-xs.webp') }}">

            <img class="green-wave no-drag"
                src="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.webp') }}" width="600px"
                height="300px" alt="">
        </picture>

        <svg class="left-circle" width="171" height="251" viewBox="0 0 171 251" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M156 125.5C156 186.527 106.527 236 45.5 236C-15.5275 236 -65 186.527 -65 125.5C-65 64.4725 -15.5275 15 45.5 15C106.527 15 156 64.4725 156 125.5Z"
                stroke="#404040" stroke-opacity="0.05" stroke-width="30" />
        </svg>

        <svg class="right-circle " width="145" height="145" viewBox="0 0 145 145" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M137.5 72.5C137.5 108.399 108.399 137.5 72.5 137.5C36.6015 137.5 7.5 108.399 7.5 72.5C7.5 36.6015 36.6015 7.5 72.5 7.5C108.399 7.5 137.5 36.6015 137.5 72.5Z"
                stroke="#61B000" stroke-width="15" />
        </svg>

    </section>
@endsection

@section('header-nav')
    <x-layout.guest-primary-header />
@endsection

@section('content')
    <section class="first-section ">
        <div class="row">
            <div class="left col-12 col-md-6 align-content-center">
                <div class="text-primary fs-1 fw-bold " aria-label="We are hiring!">
                    We are hiring!
                </div>
                <p class="fs-4 fw-medium">
                    Exciting career opportunities available.
                    <br>
                    Explore our open job positions today.
                </p>

            </div>

            <div class="right col-12 col-md-6 ">
                <div class="illus ">
                    <div class="box-icon">
                        <i data-lucide="search"></i>
                    </div>
                    <picture>
                        <source media="(min-width:2560px)" class=""
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-xl.webp') }}">
                        {{--
                        * |--------------------------------------------------------------------------
                        * | START Higher or retina display  for 1200px breakpoint
                        * |--------------------------------------------------------------------------
                        --}}
                        <source media="(min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-lg-2x.webp') }}">
                        <source media="(min-width: 1200px) and (min--moz-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-lg-2x.webp') }}">
                        <source media="(min-width: 1200px) and (-min-moz-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-lg-2x.webp') }}">
                        <source media="(min-width: 1200px) and (-o-min-device-pixel-ratio: 2/1)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-lg-2x.webp') }}">
                        <source media="(min-width: 1200px) and (min-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-lg-2x.webp') }}">

                        {{--
                        * |--------------------------------------------------------------------------
                        * | END Higher or retina display  for 1200px breakpoint
                        * |--------------------------------------------------------------------------
                        --}}
                        <source media="(min-width:1200px)" class=""
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-lg.webp') }}">

                        <source media="(min-width:1200px)" class=""
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-lg.webp') }}">
                        <source media="(min-width:768px)" class=""
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-md.webp') }}">

                        {{--
                        * |--------------------------------------------------------------------------
                        * | START Higher or retina display  for 576px breakpoint
                        * |--------------------------------------------------------------------------
                        --}}

                        <source media="(min-width: 576px) and (-webkit-min-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-md.webp') }}">
                        <source media="(min-width: 576px) and (min--moz-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-md.webp') }}">
                        <source media="(min-width: 576px) and (-min-moz-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-md.webp') }}">
                        <source media="(min-width: 576px) and (-o-min-device-pixel-ratio: 2/1)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-md.webp') }}">
                        <source media="(min-width: 576px) and (min-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-md.webp') }}">

                        {{--
                        * |--------------------------------------------------------------------------
                        * | END Higher or retina display  for 576px breakpoint
                        * |--------------------------------------------------------------------------
                        --}}

                        <source media="(min-width:576px)" class=""
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-sm.webp') }}">

                        {{--
                        * |--------------------------------------------------------------------------
                        * | START Higher or retina display  for 320px breakpoint
                        * |--------------------------------------------------------------------------
                        --}}

                        <source media="(min-width: 320px) and (-webkit-min-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-xs-2x.webp') }}">
                        <source media="(min-width: 320px) and (min--moz-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-xs-2x.webp') }}">
                        <source media="(min-width: 320px) and (-min-moz-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-xs-2x.webp') }}">
                        <source media="(min-width: 320px) and (-o-min-device-pixel-ratio: 2/1)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-xs-2x.webp') }}">
                        <source media="(min-width: 320px) and (min-device-pixel-ratio: 2)"
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-xs-2x.webp') }}">

                        {{--
                        * |--------------------------------------------------------------------------
                        * | END Higher or retina display  for 320px breakpoint
                        * |--------------------------------------------------------------------------
                        --}}
                        <source media="(min-width:320px)" class=""
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-xs.webp') }}">

                        <img src="{{ Vite::asset('resources/images/illus/sapiens-1-md.webp') }}" class="sapien"
                            width="300px" height="250px" alt="">
                    </picture>
                </div>
                <div class="box-icon">
                    <i data-lucide="briefcase"></i>
                </div>
            </div>
        </div>
    </section>

    <section class="second-section">
        @livewire('guest.job-search-input')
        <div class="px-md-5  pt-md-5 pb-md-3 ms-5">
            <em class=" ms-5">
                Currently <span></span> <span>jobs</span> available
            </em>
        </div>
        <section class="job-listing d-flex  row gap-5 mb-5 ">

            @livewire('guest.jobs-list-card', ['lazy' => true])

            @livewire('guest.job-view-pane', ['lazy' => true])

        </section>

    </section>

    <div class="modal fade sign-up-form" id="signUpForm" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content p-md-4 ">
                <div class="modal-header border-0">

                    <button type="button" class="btn-close p-1 p-md-3 mt-md-n4 me-md-n3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body ps-md-5 pe-md-3 py-md-4  ">
                    @livewire('auth.guests.sign-up', ['lazy' => true])
                </div>
            </div>

        </div>

    </div>
@endsection

@section('footer')
    <x-guest.footer />
@endsection
