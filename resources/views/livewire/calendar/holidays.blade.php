@use(Illuminate\Support\Carbon)

<div class="card border-primary mt-1 p-3 w-100 h-100">
    <section>
        <!-- Section Title -->
        <div class="row">
            <div class="col-md-8">
                <div class="text-primary fs-3 fw-bold">
                    Company Holidays
                </div>
            </div>

            <div class="col-md-4 d-flex justify-content-end align-items-center">
                <!-- Button to go to the previous year -->
                <button class="bg-transparent border-0 green-hover" id="prevYearBtn">
                    <i class="icon p-1 mx-2" data-lucide="chevron-left"></i>
                </button>
                <div class="fs-5 fw-bold" id="currentYear">{{ Carbon::now()->year }}</div>
                <!-- Button to go to the next year -->
                <button class="bg-transparent border-0 green-hover" id="nextYearBtn">
                    <i class="icon p-1 mx-2" data-lucide="chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="mt-2 scrollable-container visible-gray-scrollbar">
            <table class="table">
                <thead>
                    <tr>
                        <th>Holiday</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="holidaysList">
                    @foreach ($this->holidays as $holiday)
                        <tr wire:key="{{ $holiday->id }}">
                            <td>{{ $holiday->event }}</td>
                            <td>{{ Carbon::parse($holiday->date)->format('F, d Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>