<?php

namespace App\Livewire\Admin\Config\Performance;

use Livewire\Component;
use App\Models\PerformanceRating;
use Livewire\Attributes\Computed;

class RatingScales extends Component
{
    #[Computed]
    public function ratingScales()
    {
        return PerformanceRating::all()->mapWithKeys(function ($item) {
            return [
                $item['perf_rating_id'] => [
                    'head' => $item['perf_rating'],
                    'subhead' => ucwords($item['perf_rating_name']),
                ]
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.admin.config.performance.rating-scales');
    }
}
