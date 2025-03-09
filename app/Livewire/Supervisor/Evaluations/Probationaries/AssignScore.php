<?php

namespace App\Livewire\Supervisor\Evaluations\Probationaries;

use App\Enums\PerformanceEvaluationPeriod;
use App\Models\Employee;
use App\Models\PerformanceCategory;
use App\Models\PerformanceRating;
use App\Models\ProbationaryPerformance;
use Illuminate\Support\Carbon;
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

    public $ratings = [];

    public $comments;

    public $recommendedToRegular = false;

    public function mount()
    {
        $this->employee->loadMissing([
            'jobTitle',
            'status',
            'performancesAsProbationary',
            'performancesAsProbationary.details',
            'performancesAsProbationary.details.categoryRatings',
            'performancesAsProbationary.details.categoryRatings.rating',
            'performancesAsProbationary.details.categoryRatings.category',
        ]);
    }

    public function save()
    {
        DB::transaction(function () {
            $detail = $this->storeProbationaryEvaluation();

            $data = $this->prepareForInsertion($detail);

            DB::table('probationary_performance_ratings')->insert($data);
        });

        $this->resetExcept('routePrefix');

        $this->redirectRoute("{$this->routePrefix}.performances.probationaries.index", navigate: true);
    }

    private function storeProbationaryEvaluation(): ProbationaryPerformance
    {
        return ProbationaryPerformance::create([
            'period_id' => $this->currentPeriod->period_id,
            'evaluator' => Auth::user()->account->employee_id,
            'evaluator_comments' => $this->comments,
            'evaluator_signed_at' => now(),
            'is_final_recommend' => $this->recommendedToRegular,
        ]);
    }

    private function prepareForInsertion(ProbationaryPerformance $evaluationDetail): array
    {
        $evaluation = [];

        foreach ($this->ratings as $categoryId => $ratingId) {
            array_push($evaluation, [
                'perf_category_id' => $categoryId,
                'perf_rating_id' => $ratingId,
                'probationary_performance_id' => $evaluationDetail->probationary_performance_id,
            ]);
        }

        return $evaluation;
    }

    public function rules()
    {
        //
    }

    public function messages()
    {
        //
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
        return PerformanceRating::all()
            ->pluck('perf_rating', 'perf_rating_id')
            ->toArray();
    }

    #[Computed]
    public function currentPeriod()
    {
        return $this->employee->performancesAsProbationary()
            ->latest()->first();
    }

    #[Computed]
    public function periods()
    {
        $periods = array_map(function ($item) {
            return $item;
        }, PerformanceEvaluationPeriod::cases());

        return array_filter($periods,
            fn ($period) => $period !== PerformanceEvaluationPeriod::ANNUAL
        );
    }

    #[Computed]
    public function thirdMonthEvaluation()
    {
        $thirdMonth = PerformanceEvaluationPeriod::THIRD_MONTH->value;

        $thirdMonthEvaluation = $this->employee->performancesAsProbationary
            ->filter(function ($item) use ($thirdMonth) {
                $endDateYear = Carbon::parse($item->end_date)->year;

                return $endDateYear === now()->year &&
                    $item->period_name === $thirdMonth;
            }
            );

        return $thirdMonthEvaluation?->first()?->details?->first()
            ?->categoryRatings->map(function ($rating) {
                return (object) [
                    'category' => $rating->category->perf_category_id,
                    'rating' => $rating->rating->perf_rating_id,
                    'ratingScale' => $rating->rating->perf_rating,
                    'ratingName' => $rating->rating->perf_rating_name,
                ];
            }
            )->flatten()->toArray();
    }

    #[Computed]
    public function fifthMonthEvaluation()
    {
        $fifthMonth = PerformanceEvaluationPeriod::FIFTH_MONTH->value;

        $fifthMonthEvaluation = $this->employee->performancesAsProbationary
            ->filter(function ($item) use ($fifthMonth) {
                $endDateYear = Carbon::parse($item->end_date)->year;

                return $endDateYear === now()->year &&
                    $item->period_name === $fifthMonth;
            }
            );

        return $fifthMonthEvaluation?->first()?->details?->first()
            ?->categoryRatings->map(function ($rating) {
                return (object) [
                    'category' => $rating->category->perf_category_id,
                    'rating' => $rating->rating->perf_rating_id,
                    'ratingScale' => $rating->rating->perf_rating,
                    'ratingName' => $rating->rating->perf_rating_name,
                ];
            }
            )->flatten()->toArray();
    }

    public function render()
    {
        // dd($this->thirdMonthEvaluation, $this->fifthMonthEvaluation);
        return view('livewire.supervisor.evaluations.probationaries.assign-score');
    }
}
