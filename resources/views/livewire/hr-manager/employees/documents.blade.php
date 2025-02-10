<section id="documents" class="tab-section-employee" wire:ignore.self>
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
                <livewire:pre-employment-requirements-table :employee="$employee" />
                <livewire:employee.pre-employment.preemployment-form :employee="$employee" />
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('perPageDispatcher', () => ({
                updatePerPage() {
                    let select = document.getElementById('table-perPage');
                    let currentValue = parseInt(select.value);
                    let newValue = currentValue + 5;
                    let options = Array.from(select.options).map(option => parseInt(option.value));
                    if (options.includes(newValue)) {
                        select.value = newValue;
                        select.dispatchEvent(new Event('change'));
                    }
                }
            }));
        });
    </script>
</section>
