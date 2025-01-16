<div class="row mt-4 mb-5">

    <!-- MODALS START -->

    <x-modals.dialog id="changeTimeframe">
        <x-slot:title>
            <h1 class="modal-title fs-5">{{ __('Change Timeframe') }}</h1>
            <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>
        <x-slot:content>
            <div class="my-2">
                <!-- BACK-END REPLACE: This can be configured. Companies typically always opts for weeks, instead of specific dates. -->
                <x-form.boxed-dropdown name="interval" id="timeframe" label="{{ __('Choose a timeframe') }}" :required="true" :nonce="$nonce"
                    :options="['1' => '1 week', '2' => '2 weeks', '3' => '3 weeks']"
                    placeholder="Select type of event" />
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button type="button" class="btn btn-primary" onclick="switchModal('changeTimeframe', 'confirmTimeframe')">{{ __('Change Timeframe') }}</button>
        </x-slot:footer>
    </x-modals.dialog>
    
    <!-- Confirmational Modal -->
    <x-modals.confirmation.confirm-modal type="check" label="Confirm Timeframe" header="Change Timeframe"
    id="confirmTimeframe" message="This action will be applied automatically. Are you sure you want to proceed?"
    actionButtonTitle="Confirm" wireAction="save"/>

    <!-- END OF MODALS -->
     
    <div class="col-md-4">
        <section>
            <p class="fs-3 fw-bold">
                Extend Timeframe
            </p>

            <p>
                Manage and extend the duration of timeframe for the assessment period to ensure evaluations are completed
                within
                the designated schedule.
            </p>
        </section>
    </div>

    <div class="col-md-8">
        <section>
            <div class="card p-4 pb-3 border-0 bg-secondary-subtle">
                <div>
                    <h5 class="text-primary fw-bold">Default Timeframe: 1 week</h5>

                    <p>
                        During this timeframe, authorized personnel will be permitted to assign scores and
                        approve evaluations as part of the organization's performance assessment process.
                    </p>
                </div>
                <div class="d-inline-flex pt-2">
                    <button type="button" onclick="openModal('changeTimeframe')" class="btn btn-primary me-3 py-2">
                        {{ __('Change Timeframe') }}
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>

@script
<script>

const modalEl = document.getElementById('confirmTimeframe');
const confirmTimeframe = bootstrap.Modal.getOrCreateInstance(modalEl);

Livewire.on('changes-saved', (event) => {
    confirmTimeframe.hide();      
});

</script>
@endscript