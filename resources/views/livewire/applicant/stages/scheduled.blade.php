<section class="mb-5">
    <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">
        <span>
            <picture>
                <source media="(min-width:2560px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/green-calendar-xxl.webp') }}">
                <source media="(min-width:768px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/green-calendar-md.webp') }}">
                <source media="(min-width:576px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/green-calendar-sm.webp') }}">
                <source media="(max-width:320px)" class=""
                    srcset="{{ Vite::asset('resources/images/icons/green-calendar-xs.webp') }}">

                <img width="28" height="28" aspect-ratio="1/1" class="icon" loading="lazy"
                    src="{{ Vite::asset('resources/images/icons/green-calendar-md.webp') }}" alt="">
            </picture>
        </span>
        Schedule of Assessment
    </header>

    <div class="row flex-md-nowrap gap-5">
        <section class="d-flex flex-column col-md-6 gap-3">
            <div class="col-md-12 card border-0 bg-body-secondary text-center p-5 gap-3">
                <label for="applicant-exam-date" class="text-uppercase text-primary fw-medium">Examination</label>
                <strong id="applicant-exam-date" class="applicant-exam-date fs-4 fw-bold">
                    {{ $examStartTimeF }}
                </strong>

            </div>
            <div class="col-md-12 card border-0 bg-body-secondary text-center p-5 gap-3">
                <label for="applicant-interview-date" class="text-uppercase text-primary fw-medium">Initial
                    Interview</label>
                <strong id="applicant-interview-date" class="applicant-interview-date fs-4 fw-bold">
                    {{ $initialInterviewTimeF }}
                </strong>

            </div>
        </section>
        <div class="bg-primary text-white card border-0 col-md-6 p-5 gap-3">
            <header class="fs-4 fw-bold">
                <span>
                    <picture>
                        <source media="(min-width:2560px)" class=""
                            srcset="{{ Vite::asset('resources/images/icons/white-push-pin-xxl.webp') }}">
                        <source media="(min-width:1200px)" class=""
                            srcset="{{ Vite::asset('resources/images/icons/white-push-pin-xl.webp') }}">
                        <source media="(min-width:992px)" class=""
                            srcset="{{ Vite::asset('resources/images/icons/white-push-pin-lg.webp') }}">
                        <source media="(min-width:768px)" class=""
                            srcset="{{ Vite::asset('resources/images/icons/white-push-pin-md.webp') }}">
                        <source media="(min-width:576px)" class=""
                            srcset="{{ Vite::asset('resources/images/icons/white-push-pin-sm.webp') }}">

                        <img width="28" height="28" aspect-ratio="1/1" class="" loading="lazy"
                            src="{{ Vite::asset('resources/images/icons/white-push-pin-md.webp') }}" alt="">
                    </picture>
                </span>
                Notice
            </header>
            <p>
                Please ensure you are prepared and arrive on time for both sessions. Remember to bring a valid ID, a
                copy of the resume you uploaded. Your punctuality is important. If you have any questions or need to
                reschedule, please contact the HR department at {{ 'pri.hr.' . fake()->freeEmailDomain() }}. We wish
                youthe bestof luck in your assessments!
            </p>

            <span> <b>Attire:</b> White shirt, jeans, rubber shoes</span>
        </div>
    </div>
</section>
