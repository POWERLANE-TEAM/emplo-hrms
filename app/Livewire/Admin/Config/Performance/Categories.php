<?php

namespace App\Livewire\Admin\Config\Performance;

use App\Enums\UserPermission;
use App\Models\PerformanceCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Categories extends Component
{
    public $title = 'Add Category';

    public $state = ['title', 'shortDescription'];

    public $index;

    public $editMode = false;

    /**
     * Handle validation, authorization, and transaction before
     * persisting changes in the performance_categories table.
     * Dispatch event if changes were persisted.
     * Reset each prop to their original state.
     * Remove displayed error messages.
     *
     * @return void
     */
    public function save()
    {
        $feedbackMsg = null;

        Validator::make($this->state, [
            'title' => 'required',
            'shortDescription' => 'required',
        ])->validate();

        if ($this->editMode) {
            if (! Auth::user()->hasPermissionTo(UserPermission::UPDATE_PERFORMANCE_CATEGORIES)) {
                $this->reset();

                abort(403);
            }

            $category = PerformanceCategory::find($this->index);

            is_null($category)
                ? $feedbackMsg = __('Something went wrong.')
                : DB::transaction(function () use ($category) {
                    $category->update([
                        'perf_category_name' => Str::lower($this->state['title']),
                        'perf_category_desc' => Str::lower($this->state['shortDescription']),
                    ]);
                });
            $feedbackMsg = __('Performance category was modified successfully.');
        } else {
            if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_PERFORMANCE_CATEGORIES)) {
                $this->reset();

                abort(403);
            }
            DB::transaction(function () {
                PerformanceCategory::create([
                    'perf_category_name' => Str::lower($this->state['title']),
                    'perf_category_desc' => Str::lower($this->state['shortDescription']),
                ]);
            });
            $feedbackMsg = __('"'.Str::upper($this->state['title']).'" was added successfully.');
        }
        $this->dispatch('changes-saved', compact('feedbackMsg'));

        $this->reset();
        $this->resetErrorBag();
    }

    /**
     * @param  int  $id  Use for primary key lookup on `performance_categories` table
     * @return void
     */
    public function openEditMode(int $id)
    {
        $category = PerformanceCategory::find($id);

        if (! $category) {
            $this->dispatch('not-found', [
                'feedbackMsg' => __('Sorry, it seems like this record has been removed.'),
            ]);
        } else {
            $this->title = __('Edit Category');
            $this->editMode = true;
            $this->index = $category->perf_category_id;
            $this->state['title'] = Str::upper($category->perf_category_name);
            $this->state['shortDescription'] = Str::ucfirst($category->perf_category_desc);

            $this->dispatch('open-category-modal');
        }
    }

    /**
     * Reset all properties.
     *
     * @return void
     */
    public function restart()
    {
        if ($this->editMode) {
            $this->reset();
        }

        $this->resetErrorBag();
        $this->dispatch('reset-category-modal');
    }

    #[Computed]
    public function categories()
    {
        return PerformanceCategory::latest()->get()
            ->map(function ($item) {
                return (object) [
                    'id' => $item['perf_category_id'],
                    'name' => Str::upper($item['perf_category_name']),
                    'description' => Str::ucfirst($item['perf_category_desc']),
                ];
            }
            );
    }

    public function render()
    {
        return view('livewire.admin.config.performance.categories');
    }
}
