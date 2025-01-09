<?php

namespace App\Livewire\HrManager\Evaluations\Probationaries;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use App\Enums\PerformanceEvaluationPeriod;

class PerformanceCategoryRatings extends Component
{
    public Employee $employee;

    #[Locked]
    public $previews;

    #[Locked]
    public $yearPeriod;

    public function mount()
    {
        $this->employee->loadMissing([
            'performancesAsProbationary' => fn ($query) => $query->whereYear('end_date', $this->yearPeriod),
            'performancesAsProbationary.details.categoryRatings',
            'performancesAsProbationary.details.categoryRatings.rating',
            'performancesAsProbationary.details.categoryRatings.category',
        ]);

        $this->previews = $this->groupResultsByCategory();
        // dd($this->previews);
    }

    private function groupResultsByCategory()
    {
        return $this->employee->performancesAsProbationary->flatMap(function ($item) {
            return $item->details->flatMap(function ($subitem) use ($item) {
                return $subitem->categoryRatings->map(function ($nest) use ($item) {
                    return [
                        'category'      => $nest->category->perf_category_name,
                        'categoryDesc'  => $nest->category->perf_category_desc,
                        'period'        => $item->period_name,
                        'ratingScale'   => $nest->rating->perf_rating,
                    ];
                });                
            });
        })
        ->groupBy('category')->map(function ($group) {
            $categoryDesc = $group->pluck('categoryDesc')->first();
            $scores = $group->groupBy('period')->map(function ($item) {
                return $item->pluck('ratingScale')->first();
            });
        
            return (object) [
                'categoryDesc' => $categoryDesc,
                'scores' => $scores,
            ];
        });
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

    public function render()
    {
        return view('livewire.hr-manager.evaluations.probationaries.performance-category-ratings');
    }
}
