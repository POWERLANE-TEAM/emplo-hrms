<footer class="external shadow-lg z-0 ">
    <div class="d-flex footer-info row py-3 py-md-4 mx-1 mx-md-5">
        <section class="col-12 col-md-4 order-0 px-3 py-3  ">
            <header class="align-middle text-primary fs-4 fw-bold p-3 mb-3">
                <span class="bg-white p-2 ms-n4">
                    <x-html.pri-sm-logo></x-html.pri-sm-logo>
                </span>
                Powerlane
            </header>
            Powerlane Resources Inc. <wbr> is a manpower company <wbr> that is stationed <wbr> in Santa Rosa, Laguna.

        </section>
        <section class="col-6 col-md-2 order-3 px-3 py-3 ">
            <header id="footer-helpful-links" class="text-primary fs-6 mb-3">
                Assistance
            </header>
            <nav aria-labelledby="footer-helpful-links" class=" d-flex flex-column gap-0 row-gap-3">
                <x-nav-link href="/#" class="small unstyled" d-block :active="request()->is('#')">Help/Support</x-nav-link>
                <x-nav-link href="#" class="small d-block unstyled" :active="request()->is('#')">User Guide</x-nav-link>
            </nav>
        </section>
        <section class="col-6 col-md-2 order-3  px-3 py-3 ">
            <header id="footer-legal-links" class="text-primary fs-6 mb-3">
                Legal Links
            </header>
            <nav aria-labelledby="footer-legal-links" class="d-flex flex-column gap-0 row-gap-3">
                <x-nav-link href="#" class="small d-block unstyled" :active="request()->is('#')">Terms of Use</x-nav-link>
                <x-nav-link href="#" class="small d-block unstyled" :active="request()->is('#')">Privacy Policy</x-nav-link>
            </nav>
        </section>
        <section class=" d-grid flex-column col-12 col-md-4 order-1 order-md-4 px-3 py-3   gap-3">
            <div>
                <label for="language-selector" class="text-primary mb-1">Language</label>
                <select id="language-selector" class="form-select w-auto">
                    <option selected value="en_PH">English</option>
                    <option value="fil">Filipino</option>
                    <option value="tl">Tagalog</option>
                </select>

            </div>
        </section>
    </div>
    <div class="bg-primary text-center text-white footer-copyright py-2 py-xxl-3">
        &copy; {{ date('Y') }} Powerlane Resources. Inc.
    </div>
</footer>
