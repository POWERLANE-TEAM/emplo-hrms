@use(Illuminate\Support\Carbon)
@use(App\Enums\EmploymentStatus)

<div wire:ignore>
    <section class="px-4 mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="text-primary fs-3 fw-bold d-flex align-items-center"">
                    {{ __('Contract Overview') }}
                </div>
            </div>
        </div>

        <!-- BACK-END REPLACE NOTE:
        If the status of the employee is regular, then the expiration date is indefinite.
        If probationary, only then there will be an expiration date. -->

        <section class=" row mt-3">
            <div class="col-md-4">
                <!-- BACK-END REPLACE: Employment, Status, Monthly Salary -->
                <p class="pb-2">
                    <strong>{{ __('Employment Status: ') }}</strong>
                    {{ $this->employee->status->emp_status_name }}
                </p>
                <p class="pb-2"><strong>Status: </strong>Active</p>
                {{-- <p class="pb-2"><strong>Monthly Salary: </strong> ₱15,000</p> --}}
            </div>

            <div class="col-md-4">
                <p class="pb-2"><strong>{{ __('Start Date') }}</strong>
                    {{ Carbon::make($this->employee->lifecycle->started_at)->format('F d, Y') ?? __('Unknown') }}
                </p>
                <p class="pb-2"><strong>{{ __('Expiry Date') }}: </strong>
                    {{ $this->employee->status->emp_status_name === EmploymentStatus::PROBATIONARY->label()
                            ? Carbon::parse($this->employee->lifecycle->started_at)->addMonths(6)->format('F d, Y')
                            : __('Indefinite')
                    }}
                </p>
            </div>

            <div class="col-md-4">
                <div class="d-flex flex-column">
                    <div
                        x-data="{ uploading: false, progress: 0 }"
                        x-on:livewire-upload-start="uploading = true"
                        x-on:livewire-upload-finish="uploading = false"
                        x-on:livewire-upload-cancel="uploading = false"
                        x-on:livewire-upload-error="uploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                    >
                        <div>
                            <div x-show="uploading" x-transition>
                                <progress max="100" x-bind:value="progress"></progress>
                                <div class="fs-5 fw-semibold text-muted">{{ __('Uploading...') }}</div>
                            </div>
                            <div x-show="! uploading" x-transition:.enter.duration.400ms>
                                <label for="contract-upload" class="btn btn-primary btn-lg w-100 mb-2 d-flex align-items-center justify-content-center">
                                    <i data-lucide="upload" class="icon icon-large me-2"></i>
                                    {{ __('Renew Contract') }}
                                </label>
                                <input wire:model="contract" 
                                    type="file" class="d-none" 
                                    name="contract" 
                                    id="contract-upload" 
                                    accept="application/pdf" />

                                <label for="addendum-upload" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                                    <i data-lucide="plus" class="icon icon-large me-2"></i>
                                    {{ __('Add an Addendum') }}
                                </label>
                                <input wire:model="addendum"
                                    type="file" 
                                    id="addendum-upload" 
                                    class="d-none" 
                                    accept="application/pdf" /> 
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <livewire:employee.tables.individual-employee-contracts-table :$routePrefix :$employee />
</div>

@script
<script>
    $wire.on('contractUploaded', (event) => {
        const eventPayload = event[0];
        showToast(eventPayload.type, eventPayload.message);
    });

    $wire.on('addendumUploaded', (event) => {
        const eventPayload = event[0];
        showToast(eventPayload.type, eventPayload.message);
    });
</script>
@endscript