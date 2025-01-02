<section id="documents" class="tab-section d-none">
    <div class="row">
        <div class="col d-flex align-items-center justify-content-start">
            <div class="hover-opacity pe-auto">

                <i data-lucide="arrow-left" class="icon icon-slarge ms-2 text-blue-info"
                    data-sub-section="information-details"></i>
                <a href="#" id="toggle-information" class="text-link-blue text-decoration-underline fs-5">
                    Back to Information
                </a>
            </div>
        </div>

        <section class="row pt-3">
            <div class="col">
                <livewire:employee-documents-table :employee="$employee" />
            </div>
        </section>
    </div>
</section>
