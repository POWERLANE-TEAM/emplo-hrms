<?php

namespace App\Livewire\Supervisor\Evaluations\Regulars;

use App\Models\Employee;
use App\Models\PerformanceCategory;
use App\Models\PerformanceRating;
use App\Models\RegularPerformance;
use App\Models\RegularPerformancePeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class AssignScore extends Component
{
    public Employee $employee;

    #[Locked]
    public $routePrefix;

    public $finalRating;

    public $ratings = [];

    public $comments;

    public $performanceScale;

    public function mount()
    {
        $this->employee->loadMissing([
            'jobTitle',
            'status',
        ]);
    }

    // public function rules()
    // {
    //     return [
    //         'ratings.*' => 'required',
    //         'comments' => 'nullable',
    //     ];
    // }

    // public function messages()
    // {
    //     return [
    //         'ratings.*' => __('Rating is required.'),
    //         // 'comments' => __(),
    //     ];
    // }

    public function save()
    {
        // dd($this->ratings);
        $detail = RegularPerformance::create([
            'period_id' => RegularPerformancePeriod::latest()->first()->period_id,
            'evaluatee' => $this->employee->employee_id,
            'evaluator' => Auth::user()->account->employee_id,
            'evaluator_comments' => $this->comments,
            'evaluator_signed_at' => now(),
        ]);

        $evaluation = [];

        foreach ($this->ratings as $categoryId => $ratingId) {
            array_push($evaluation, [
                'perf_category_id' => $categoryId,
                'perf_rating_id' => $ratingId,
                'regular_performance_id' => $detail->regular_performance_id,
            ]);
        }

        DB::table('regular_performance_ratings')->insert($evaluation);

        $this->resetExcept('routePrefix');

        $this->redirectRoute("{$this->routePrefix}.performances.regulars.index");
    }

    #[Computed]
    public function categories()
    {
        return PerformanceCategory::all()
            ->map(function ($item) {
                return (object) [
                    'id' => $item->perf_category_id,
                    'name' => $item->perf_category_name,
                    'description' => $item->perf_category_desc,
                ];
            });
    }

    #[Computed]
    public function ratingScales()
    {
        return PerformanceRating::all()->map(function ($item) {
            return (object) [
                'id' => $item->perf_rating_id,
                'scale' => $item->perf_rating,
                'name' => $item->perf_rating_name,
            ];
        });
    }

    public function render()
    {
        return view('livewire.supervisor.evaluations.regulars.assign-score');
    }
}
