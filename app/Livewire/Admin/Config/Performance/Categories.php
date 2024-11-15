<?php

namespace App\Livewire\Admin\Config\Performance;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use App\Models\PerformanceCategory;

class Categories extends Component
{
    #[Computed]
    public function categories()
    {
        return PerformanceCategory::all()->mapWithKeys(function ($item) {
            return [
                $item['perf_category_id'] => [
                    'head' => Str::upper($item['perf_category_name']),
                    'subhead' => $item['perf_category_desc'],
                ]
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.admin.config.performance.categories');
    }
}
