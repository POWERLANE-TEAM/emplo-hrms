<?php

namespace App\Livewire\HrManager\Evaluations\Regulars;

use App\Models\RegularPerformance;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PerformanceCategoryRatings extends Component
{
    public RegularPerformance $performance;

    #[Locked]
    public $previews;

    public function mount()
    {
        $key = sprintf(config('cache.keys.performance.regulars.evaluation'),
            $this->performance->regular_performance_id
        );

        if (Cache::has($key)) {
            $this->previews = Cache::get($key);
        } else {
            $this->performance->categoryRatings->loadMissing('category');

            $expiration = config('duration.annual');

            $this->previews = Cache::remember($key, $expiration, function () {
                return $this->performance->categoryRatings->map(function ($item) {
                    return (object) [
                        'category' => $item->category->perf_category_name,
                        'categoryDesc' => $item->category->perf_category_desc,
                        'ratingScale' => $item->rating->perf_rating,
                        'ratingName' => $item->rating->perf_rating_name,
                    ];
                });
            });
        }
    }

    public function render()
    {
        return view('livewire.hr-manager.evaluations.regulars.performance-category-ratings');
    }
}
