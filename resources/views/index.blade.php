@php
    $nonce = csp_nonce();
@endphp

@extends('layout.guest-primary', ['description' => 'Guest Layout', 'nonce' => $nonce])

@section('head')
    <title>Home Page</title>
    <script src="build/assets/nbp.min.js" defer></script>
    @vite(['resources/js/index.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
@endsection


@section('content')
    <section class="first-section ">
        <div class="row">
            <div class="left col-12 col-md-6 align-content-center">
                <div class="text-primary fs-1 fw-bold tw-tracking-wide" aria-label="We are hiring!">
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
                        <source media="(min-width:1200px)" class=""
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-lg.webp') }}">
                        <source media="(min-width:768px)" class=""
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-md.webp') }}">
                        <source media="(min-width:576px)" class=""
                            srcset="{{ Vite::asset('resources/images/illus/sapiens-1-sm.webp') }}">

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
        <div class="tw-px-[5rem] tw-pt-[2.5rem] tw-pb-[1rem]">
            <em>
                Currently <span></span> <span>jobs</span> available
            </em>
        </div>
        <section class="job-listing d-flex row gap-5 mb-5">

            @livewire('guest.jobs-list-card', ['lazy' => true])

            @livewire('guest.job-view-pane', ['lazy' => true])

        </section>

    </section>

    <div class="modal fade signUp-form" id="signUpForm" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content p-md-4 ">
                <div class="modal-header border-0">

                    <button type="button" class="btn-close p-1 p-md-3 mt-md-n4 me-md-n3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body px-md-5 py-md-4">
                    @livewire('auth.sign-up', ['lazy' => true])
                </div>
            </div>


        </div>

    </div>
@endsection
