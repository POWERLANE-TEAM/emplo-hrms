@use('App\Enums\ApplicationStatus')
<form wire:submit.prevent="store" style="display: contents">
    <input type="hidden" name="applicationId" wire:model="applicationId" value="{{ $application->application_id }}">
    <input type="hidden" name="applicationStatusId" wire:model="applicationStatusId"
        value="{{ ApplicationStatus::REJECTED->value }}">

    @if ($errors->any())
        @php
            $this->dispatch('resume-rejection-error');
        @endphp
    @endif

    <button type="submit" id="applicant-decline-resume" name="submit"
        class="btn btn-lg btn-danger flex-grow-1">Decline</button>
</form>
