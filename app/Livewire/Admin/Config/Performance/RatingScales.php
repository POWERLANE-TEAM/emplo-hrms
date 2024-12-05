<?php

namespace App\Livewire\Admin\Config\Performance;

use App\Enums\UserPermission;
use App\Models\PerformanceRating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RatingScales extends Component
{
    public $title = 'Add Rating Scale';

    #[Validate([
        'state.scale' => 'required|unique:performance_ratings,perf_rating|numeric|min:0',
        'state.name' => 'required',
    ])]
    public $state = [
        'scale' => null,
        'name' => '',
    ];

    public $editMode = false;

    public $index;

    public function save()
    {
        $feedbackMsg = null;

        if ($this->editMode) {
            if (! Auth::user()->hasPermissionTo(UserPermission::UPDATE_PERFORMANCE_RATING_SCALES)) {
                $this->reset();

                abort(403);
            }

            $this->validateOnly('state.name');

            $ratingScale = PerformanceRating::find($this->index);

            is_null($ratingScale)
                ? $feedbackMsg = __('Something went wrong.')
                : DB::transaction(function () use ($ratingScale) {
                    $ratingScale->update([
                        'perf_rating' => $this->state['scale'],
                        'perf_rating_name' => $this->state['name'],
                    ]);
                });
            $feedbackMsg = __('Performance rating scale was modified successfully.');
        } else {
            if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_PERFORMANCE_RATING_SCALES)) {
                $this->reset();

                abort(403);
            }

            $this->validate();

            DB::transaction(function () {
                PerformanceRating::create([
                    'perf_rating' => $this->state['scale'],
                    'perf_rating_name' => $this->state['name'],
                ]);
            });
            $feedbackMsg = __('"'.$this->state['scale'].' = '.ucwords($this->state['name']).'" was added successfully.');
        }
        $this->dispatch('changes-saved', compact('feedbackMsg'));

        $this->reset();
        $this->resetErrorBag();
    }

    public function openEditMode(int $id)
    {
        $rating = PerformanceRating::find($id);

        if (! $rating) {
            $this->dispatch('not-found', [
                'feedbackMsg' => __('Sorry, it seems like this record has been removed.'),
            ]);
        } else {
            $this->title = __('Edit Rating Scale');
            $this->editMode = true;
            $this->index = $rating->perf_rating_id;
            $this->state['scale'] = $rating->perf_rating;
            $this->state['name'] = $rating->perf_rating_name;

            $this->dispatch('open-rating-scale-modal');
        }
    }

    public function restart()
    {
        if ($this->editMode) {
            $this->reset();
        }

        $this->resetErrorBag();
        $this->dispatch('reset-rating-scale-modal');
    }

    #[Computed]
    public function ratings()
    {
        return PerformanceRating::latest()->get()
            ->map(function ($item) {
                return (object) [
                    'id' => $item['perf_rating_id'],
                    'scale' => $item['perf_rating'],
                    'name' => $item['perf_rating_name'],
                ];
            }
            );
    }

    public function render()
    {
        return view('livewire.admin.config.performance.rating-scales');
    }
}
