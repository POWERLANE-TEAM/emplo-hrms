

<div class="bg-body-secondary rounded-3 col p-3 px-lg-5 py-md-4 text-center position-relative">
    @if ($isFinalAssessment)
        <button class="btn position-absolute text-primary top-0 end-0 m-1" type="button" data-bs-toggle="modal"
            data-bs-target="#edit-final-interview">
            <i class="icon icon-large" data-lucide="pencil-line"></i>
        </button>
    @endif

    <label for="applicant-final-interview-date" class="d-block text-uppercase text-primary fw-medium mt-2">Final
        Interview</label>
    <strong id="applicant-final-interview-date" class="applicant-final-interview-date fs-4 fw-bold">
        {{ $slot }}
    </strong>

</div>
