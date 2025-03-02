<div class="d-flex justify-content-between" style="max-width:2170px;">
    @if ($previousApplicant)
        <a href="{{ route('employee.application.show', ['application' => $previousApplicant->application_id]) }}"
            class="btn btn-outline-primary"><span>Prev</span>ious Applicant</a>
    @else
        <div class="opacity-0"></div>
    @endif
    @if ($nextApplicant)
        <a href="{{ route('employee.application.show', ['application' => $nextApplicant->application_id]) }}"
            class="btn btn-primary">Next Applicant</a>
    @endif
</div>
