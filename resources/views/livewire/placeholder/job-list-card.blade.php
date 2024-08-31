<sidebar class="nav nav-tabs col-12 col-md-5 " id="jobs-list" role="tablist" nonce="csp_nonce()">

    @for ($i = 0; $i < 3; $i++)
        <li class="card nav-item ps-0 " role="presentation">
            <button class="nav-link d-flex flex-row column-gap-4" role="tab">
                <div class="placeholder-glow col-4 pt-3 px-2 ">
                    <span class="placeholder" style="width: calc(4.5rem + 2vw); height: calc(4.5rem + 2vw);"></span>
                </div>
                <div class="col-7 text-start">
                    <header>
                        <hgroup class="placeholder-glow d-flex flex-column mb-3">
                            <h4 class=" text-black placeholder-glow">
                                <span class="placeholder py-3  col-8"></span>
                            </h4>
                            <p class="text-primary placeholder-glow">
                                <span class="placeholder py-2  col-12"></span>
                            </p>
                        </hgroup>
                    </header>


                    <div class="pe-5 overflow-hidden">
                        <p class="d-flex card-text placeholder-glow gap-1 mb-1">
                            <span class="placeholder py-1 col-10"></span>
                            <span class="placeholder py-1 col-4"></span>
                            <span class="placeholder py-1 col-7"></span>
                            <span class="placeholder py-1 col-2"></span>
                        </p>

                        <p class="d-flex card-text placeholder-glow gap-1 mb-1">
                            <span class="placeholder py-1  col-11"></span>
                            <span class="placeholder py-1  col-6"></span>
                            <span class="placeholder py-1  col-3"></span>
                        </p>
                    </div>
                </div>
            </button>
        </li>
    @endfor
</sidebar>
