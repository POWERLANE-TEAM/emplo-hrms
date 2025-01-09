<sidebar class="nav nav-tabs col-12 col-md-4 ms-auto" id="jobs-list" role="tablist" aria-busy="true" nonce="csp_nonce()">

    @for ($i = 0; $i < 3; $i++)
        <li class="card nav-item ps-0 " role="presentation">
            <button class="nav-link d-flex flex-row px-md-5 py-md-4" role="tab" aria-busy="true"
                aria-label="Loading job list" aria>
                <div class="col-10 px-4 text-start">
                    <header>
                        <hgroup class="placeholder-glow d-flex flex-column mb-1">
                            <div class=" text-black placeholder-glow">
                                <span class="placeholder py-3  col-8"></span>
                            </div>
                            <p class="text-primary placeholder-glow">
                                <span class="placeholder py-2  col-12"></span>
                            </p>
                        </hgroup>
                    </header>


                    <div class="pe-5 overflow-hidden">
                        <p class="d-flex card-text placeholder-glow gap-1 mb-1">
                            <span class="placeholder py-1 col-4"></span>
                            @php
                                $randomNumber = rand(0, 1);
                                $firstColClass = $randomNumber ? 'col-5' : 'col-3';
                                $lastColClass = $randomNumber ? 'col-3' : 'col-4';
                            @endphp

                            <span class="placeholder py-1 {{ $firstColClass }}"></span>
                            <span class="placeholder py-1 {{ $lastColClass }}"></span>

                        </p>
                    </div>
                </div>
            </button>
        </li>
    @endfor
</sidebar>
