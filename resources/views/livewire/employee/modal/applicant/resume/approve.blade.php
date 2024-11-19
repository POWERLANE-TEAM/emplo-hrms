@use ('Illuminate\View\ComponentAttributeBag')

<div>
    <div class="modal fade" tabindex="-1" id="{{ $modalId }}" aria-label="Approve Job Application" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3 p-md-4">
                <x-modals.head />

                <x-modals.body>

                    <x-modals.body-header class="mb-2" header="Set Schedule"
                        message="Set examination and interview schedule" :messageAttributes="new ComponentAttributeBag(['class' => 'text-center'])" />

                    @livewire('employee.applicants.set-examination-date', ['application' => $application, 'routePrefix' => $routePrefix])
                    @livewire('employee.applicants.set-init-interview-date', ['application' => $application, 'routePrefix' => $routePrefix])

                    <div class="d-flex w-100 px-3 px-lg-4">
                        <button type="button" class="btn btn-lg btn-primary flex-grow-1" name="submit"
                            id="applicant-profile-sched">Submit</button>
                    </div>

                </x-modals.body>
            </div>
        </div>
    </div>
</div>
