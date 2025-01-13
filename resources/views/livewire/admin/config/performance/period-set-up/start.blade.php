<div class="row mt-4 mb-5">

    <!-- MODALS START -->

    <x-modals.dialog id="changeStartDate">
        <x-slot:title>
            <h1 class="modal-title fs-5">{{ __('Change Evaluation Start Date') }}</h1>
            <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>
        <x-slot:content>
            <div class="my-2">
                <!-- BACK-END REPLACE: Start Date -->
                <x-form.boxed-date id="expiry_date" label="{{ __('Date') }}" :nonce="$nonce" :required="true"
                    placeholder="Date of Overtime" />
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button type="button" class="btn btn-primary"
                onclick="switchModal('changeStartDate', 'confirmStartDate')">{{ __('Change Start Date') }}</button>
        </x-slot:footer>
    </x-modals.dialog>

    <!-- Confirmational Modal -->
    <x-modals.confirmation.confirm-modal type="check" label="Confirm Start Date" header="Change Evaluation Start Date"
        id="confirmStartDate" message="This action will be applied automatically. Are you sure you want to proceed?"
        actionButtonTitle="Confirm" wireAction="save" />

    <!-- END OF MODALS -->

    <div class="col-md-4">
        <section>
            <p class="fs-3 fw-bold">
                Evaluations Start Date
            </p>

            <p>
                Configure the commencement date for employees' performance evaluations.
            </p>
        </section>
    </div>

    <div class="col-md-8">
        <section>
            <div class="card p-4 pb-3 border-0 bg-secondary-subtle">
                <div>
                    <!-- BACK-END REPLACE: Default Commencement Date -->
                    <h5 class="text-primary fw-bold">Commencement Date: January 1</h5>

                    <p>

                        The start date of evaluations signifies the official commencement of the annual performance
                        evaluation period for regular employees.

                        <b>For probationary employees:</b> their start date will be automatically opened based on their
                        date of employment.

                        <a href="/information-centre?section=evaluation-policy" id="toggle-information"
                            class="text-link-blue text-decoration-underline fs-7 hover-opacity">
                            Learn more about performance evaluations.
                        </a>
                    </p>
                </div>
                <div class="d-inline-flex pt-2">
                    <button type="button" onclick="openModal('changeStartDate')" class="btn btn-primary me-3 py-2">
                        {{ __('Change Start Date') }}
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>

@script
<script>

const modalEl = document.getElementById('confirmStartDate');
const confirmStartDate = bootstrap.Modal.getOrCreateInstance(modalEl);

Livewire.on('changes-saved', (event) => {
    confirmStartDate.hide();      
});

</script>
@endscript