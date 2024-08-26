<x-html>

    <x-html.head description=" Employee Dashboard">
        <title>Home Page</title>

        {{-- Critical Assets that will cause cumulative shift if late loaded --}}
        <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
        <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
        <script nonce="{{ csp_nonce() }}">
            lucide.createIcons();
        </script>

        @vite(['resources/js/employee/dashboard.js'])

    </x-html.head>

    <body class=" ">
        <x-employee.nav.main-menu :sidebar_expanded="true" class="position-sticky top-0 start-0"></x-employee.nav.main-menu>
        <main class="main">
            {{-- <section class="job-listing  d-flex tw-px-[5rem] tw-gap-12 ">
                <sidebar class="nav nav-tabs col-5 " role="tablist">

                    <?php
                for ($i = 0; $i < 20; $i++) {
                ?>

                    <li class="card nav-item ps-0 " role="presentation">
                        <button class="nav-link d-flex flex-row tw-gap-x-6" id="{{ $i }}-tab"
                            data-bs-toggle="tab" data-bs-target="#{{ $i }}-tab-pane" role="tab">
                            <div class="col-4 pt-3 px-2 ">
                                <img src="http://placehold.it/74/74" alt="">
                            </div>
                            <div class="col-7 text-start">
                                <header>
                                    <hgroup>
                                        <h4 class="card-title text-black mb-0">Card title</h4>
                                        <p class="fs-4 text-primary">Card title</p>
                                    </hgroup>
                                </header>
                                <div class="">

                                    <div class="card-text text-black">content.</div>
                                    <div class="card-text text-black">content.</div>
                                </div>
                            </div>
                        </button>


                    </li>
                    <?php
                    }
                ?>
                </sidebar>
                <article class="job-view tab-content col-6">
                    <div class="job-content tab-pane fade show active card border-0 bg-secondary w-100 "
                        id="#1-tab-pane" role="tabpanel" aria-labelledby="-tab">
                        <div class="d-flex">
                            <header>
                                <hgroup>
                                    <h4 class="card-title text-primary fw-bold mb-0">Job Position</h4>
                                    <p class="fs-6 text-black ">Job Location</p>
                                </hgroup>
                                <a href="" hreflang="en-PH" class="btn btn-primary mt-1 mb-4" role="navigation"
                                    aria-label="Apply">Apply <span><i data-lucide="external-link"></i></span></a>

                                <p class="job-descr card-text">
                                    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Blanditiis
                                    possimus expedita ipsum atque magni laboriosam vel veritatis, suscipit eum
                                    quam quaerat cupiditate veniam voluptatem. Cum pariatur quisquam totam vero
                                    natus?
                                    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Blanditiis
                                    possimus expedita ipsum atque magni laboriosam vel veritatis, suscipit eum
                                    quam quaerat cupiditate veniam voluptatem. Cum pariatur quisquam totam vero
                                    natus?
                                </p>
                                <button href=""
                                    class="bg-transparent border border-0 text-decoration-underline text-black ps-0">
                                    Show More <span><i data-lucide="chevron-down"></i></span>
                                </button>
                            </header>
                            <div>
                                <button class="bg-transparent border border-0">
                                    <i data-lucide="more-vertical"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            </section>
            <x-html.test-elements></x-html.test-elements> --}}
        </main>

        <x-employee.footer></x-employee.footer>
    </body>
</x-html>
