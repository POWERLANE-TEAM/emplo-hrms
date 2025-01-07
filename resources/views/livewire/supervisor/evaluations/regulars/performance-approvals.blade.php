
<section class="mb-5 mt-3">
    <div class="d-flex row align-items-stretch">
        {{-- Left Section: Performance Approvals --}}
        <section class="col-md-5 d-flex">
            <div class="w-100">
                <div class="p-2">
                    @include('components.includes.tab_navs.eval-result-navs')
                </div>
                
                <div class="card border-primary mt-1 px-5 py-4 w-100">
                    <section id="overview" class="tab-section">
                        <div class="text-primary fs-3 fw-bold text-center">
                            {{ __('Overview') }}
                        </div>

                        <div class="text-center pt-3 pb-4">
                            <p class="fw-bold fs-4">
                                {{ __("{$this->finalRating['ratingAvg']} - {$this->finalRating['performanceScale']}") }}
                            </p>
                            <p class="text-muted">{{ __('Final Rating & Performance Scale') }}</p>
                        </div>

                        {{-- SECTION: Main Approvals --}}
                        <div class="pb-3">
                            <p class="fw-medium fs-5">{{ __('Main Approvals') }}</p>

                            <div class="ps-4 pe-2 py-3">
                                <x-form.checkbox container_class="" :nonce="$nonce" id="supervisor_approval" name="supervisor_approval"
                                    class="checkbox checkbox-primary" disabled checked>

                                    <x-slot:label>
                                        <div class="d-flex flex-column">
                                            <div class="fs-5">
                                                {{ $performance->employeeEvaluator->full_name }}
                                            </div>
                                            <div class="text-primary">
                                                {{ $performance->employeeEvaluator->jobTitle->job_title }}
                                            </div>
                                        </div>
                                    </x-slot:label>
                                </x-form.checkbox>
                            </div>

                            {{-- Head Department Approval --}}
                            <div class="ps-4 pe-2 py-2">
                                <div class="row">
                                    <div class="col-9">
                                        <x-form.checkbox container_class="" :nonce="$nonce" id="head_dept_approval" name="head_dept_approval"
                                            class="checkbox checkbox-primary" disabled :checked="$performance->secondary_approver_signed_at">
        
                                            <x-slot:label>
                                                <div class="d-flex flex-column">
                                                    <div class="fs-5">
                                                        {{ $performance->secondaryApprover->full_name ?? 
                                                            $performance->employeeEvaluatee->jobTitle->jobFamily->head->full_name ??
                                                            __('Awaiting Approval')
                                                        }}
                                                    </div>
                                                    <div class="text-primary">
                                                        {{ $performance->secondaryApprover->jobTitle->job_title ?? 
                                                            $performance->employeeEvaluatee->jobTitle->jobFamily?->head?->jobTitle?->job_title
                                                        }}
                                                    </div>
                                                </div>
                                            </x-slot:label>
                                        </x-form.checkbox>
                                    </div>  
                                    @if (is_null($performance->secondary_approver_signed_at))
                                        <div class="col-3">
                                            <x-status-badge color="info">Pending</x-status-badge>
                                        </div>
                                    @endif                                  
                                </div>
                            </div>
                        </div>

                        <div class="pb-3">
                            <p class="fw-medium fs-5">{{ __('Human Resources Department') }}</p>
                            <div class="ps-4 pe-2 py-3">
                                <div class="row">
                                    <div class="col-9">
                                        <x-form.checkbox container_class="" :nonce="$nonce" id="hr_staff_approval" name="hr_staff_approval"
                                            class="checkbox checkbox-primary" disabled :checked="$performance->third_approver_signed_at">

                                            <x-slot:label>
                                                <div class="d-flex flex-column">
                                                    <div class="fs-5">
                                                        {{ $performance->thirdApprover->full_name ?? $this->randomHrdStaff }}
                                                    </div>
                                                    <div class="text-primary">
                                                        {{ $performance->thirdApprover->jobTitle->job_title ?? __('HR Staff') }}
                                                    </div>
                                                </div>
                                            </x-slot:label>
                                        </x-form.checkbox>
                                    </div>
                                    @if (is_null($performance->third_approver_signed_at))
                                        <div class="col-3">
                                            <x-status-badge color="info">Pending</x-status-badge>
                                        </div>
                                    @endif                                         
                                </div>
                            </div>

                            <div class="ps-4 pe-2 py-2">
                                <div class="row">
                                    <div class="col-9">
                                        <x-form.checkbox container_class="" :nonce="$nonce" id="hr_head_approval"
                                            name="hr_head_approval" class="checkbox checkbox-primary" disabled :checked="$performance->fourth_approver_signed_at">

                                            <x-slot:label>
                                                <div class="d-flex flex-column">
                                                    <div class="fs-5">
                                                        {{ $performance->fourthApprover->full_name ?? $this->hrdManager }}
                                                    </div>
                                                    <div class="text-primary">
                                                        {{ $performance->fourthApprover->jobTitle->job_title ?? __('HRD Manager') }}
                                                    </div>
                                                </div>
                                            </x-slot:label>
                                        </x-form.checkbox>
                                    </div>

                                    @if (is_null($performance->fourth_approver_signed_at))
                                        <div class="col-3">
                                            <x-status-badge color="info">Pending</x-status-badge>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- <div>
                            <button onclick="openModal('approvalHistoryModal')" class="btn fw-medium underline hover-opacity underline">Approval History</button>
                        </div> --}}
                    </section>

                    <section id="comments" class="tab-section">
                        {{ $performance->evaluator_comments ?? __('No comments provided') }}
                    </section>
                </div>
            </div>
        </section>

        {{-- Right Section: Performance Category --}}
        <section class="col-md-7 d-flex">
            <div class="w-100">
                <livewire:supervisor.evaluations.regulars.performance-category-ratings :$performance />
            </div>
        </section>
    </div>

    {{-- Buttons --}}
    @if (is_null($performance->secondary_approver_signed_at))
        <div class="col-5 ps-3 pe-4">
            <button
                wire:click="markAsReceived"
                type="submit" 
                name="submit"
                class="btn btn-primary btn-lg fw-regular col-6 w-100"
                >{{ __('Mark As Received') }}
            </button>
        </div>
    @endif
</section>