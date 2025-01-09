<?php

namespace App\Livewire\Supervisor\Evaluations;

use Livewire\Component;
use App\Models\Employee;
use App\Models\PerformanceDetail;
use App\Models\PerformancePeriod;
use App\Models\PerformanceRating;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use App\Models\PerformanceCategory;
use Illuminate\Support\Facades\Auth;

class AssignScore extends Component
{
    public $finalRating;

    public $performanceScale;

    // #[Validate]
    public $ratings = [];

    public $comments;

    public $employee;

    public function mount(Employee $employee)
    {
        unset($this->periods, $this->categories, $this->ratingScales,);

        $this->employee = $employee;

        $existingRatings = PerformanceDetail::where('evaluatee', $this->employee->employee_id)
            ->with('categoryRatings')
            ->get();
        
        collect($this->periods)->each(function ($period) use ($existingRatings) {
            collect($this->categories)->each(function ($category) use ($existingRatings, $period) {
                $existingRating = $existingRatings->firstWhere('perf_period_id', $period->id);

                if ($existingRating && $existingRating->categoryRatings) {
                    $existingRatingCategory = $existingRating->categoryRatings->firstWhere('perf_category_id', $category->id);

                    if ($existingRatingCategory) {
                        $this->ratings[$category->id][$period->id] = $existingRatingCategory->perf_rating_id;
                    }
                } else {
                    $this->ratings[$category->id][$period->id] = null;
                }
            });
        });

        $this->updatePerformanceRating();
    }
    

    public function boot()
    {
        $this->updatePerformanceRating();
    }

    private function updatePerformanceRating()
    {
        if ($this->checkNoEmptyPeriods()) {
            $this->finalRating = $this->getFinalRatingAvg();

            $this->performanceScale = $this->getPerformanceScale($this->finalRating);
        }
    }


    private function getPerformanceScale($finalRating)
    {
        $rounded = (int) round($finalRating);

        if ($rounded >= 1 && $rounded <= 4) {
            return PerformanceRating::where('perf_rating', $rounded)
                ->first()
                ->perf_rating_name;
        }
    }
    
    /**
     * Check if all periods from the 3rd to the last have values.
     */
    private function checkNoEmptyPeriods(): bool
    {
        foreach ($this->periods as $period) {
            if (
                empty($this->ratings[$period->id]) || 
                in_array(null, $this->ratings[$period->id], true)
            ) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Calculate the average final rating.
     */
    private function getFinalRatingAvg()
    {
        $total = 0;
        $count = 0;
    
        foreach ($this->periods as $period) {
            foreach ($this->ratings[$period->id] ?? [] as $rating) {
                if ($rating) {
                    $total += $rating;
                    $count++;
                }
            }
        }
    
        return $count > 0 
            ? number_format(($total / $count), 2, '.', '')
            : $this->reset('finalRating');
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
        $periods = collect($this->ratings)
            ->map(function ($rating) {
                return collect($rating)->filter(fn ($value) => ! is_null($value));
            });
    
        $periods->each(function ($validPeriods, $categoryId) {
            $validPeriods->each(function ($score, $periodId) use ($categoryId) {
                DB::transaction(function () use ($periodId, $score, $categoryId) {
                    $detail = PerformanceDetail::firstOrCreate(
                        [
                            'perf_period_id' => $periodId,
                            'evaluatee' => $this->employee->employee_id
                        ],
                        [
                            'evaluator' => Auth::id(),
                            'evaluatee_signed_at' => now(),
                            'evaluator_comments' => $this->comments,
                        ]
                    );
        
                    if ($score) {
                        $category = PerformanceCategory::findOrFail($categoryId);
                        
                        $category->ratings()->syncWithoutDetaching([
                            $score => ['perf_detail_id' => $detail->perf_detail_id]
                        ]);
                    }                    
                });

            });
        });
    
        // $this->resetExcept('ratings', 'employee');
    }
    

    #[Computed]
    public function periods()
    {
        return PerformancePeriod::with('details')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id' => $item->perf_period_id,
                    'name' => $item->perf_period_name,
                ];
            })->reject(function ($item) {
                return match ($this->employee->status->emp_status_name) {
                    'Regular' => in_array($item->name, ['Third Month', 'Fifth Month', 'Final Month']),
                    'Probationary' => $item->name === 'Annual',
                    default => abort (403, __('This employee is neither of Regular or Probationary status.'))
                };
            });
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

    public function render()
    {
        return view('livewire.supervisor.evaluations.assign-score');
    }
}
