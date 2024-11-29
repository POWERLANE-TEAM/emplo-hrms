@use ('Illuminate\View\ComponentAttributeBag')


<div>

    @include('livewire.applicant.application.application-wizard-progress-bar')
    <div class="d-contents">
        <section class="container " aria-label="Application First Step">

            <div class="my-3 col-md-8 mx-auto">


                <div class="boxed">
                    {{-- <livewire:form.applicant.application.resume-upload /> --}}
                    <x-filepond::upload wire:model="resumeFile" {{-- name="resume.file" --}} instant-upload="false"
                        {{--   max-file-size="{{ $this->fileMaxSize * 1024 }}" --}} accept="application/pdf" />
                </div>
                @include('components.form.input-feedback', [
                    'feedback_id' => 'resumeFile',
                    'message' => $errors->first('resume.file'),
                ])

            </div>


        </section>
        @include('livewire.applicant.application.application-wizard-nav-btn')
    </div>
</div>
