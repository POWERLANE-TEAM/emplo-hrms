<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class RecentActivityLogs extends Component
{
    use WithPagination;

    protected $interval;

    public function mount()
    {
        $this->interval = Carbon::now()->copy()->subDay();
    }

    #[Computed]
    public function logs()
    {
        return Activity::where('created_at', '>=', $this->interval)
            ->latest()
            ->simplePaginate(10)
            ->through(function ($item) {
                return (object) [
                    'id' => $item->id,
                    'description' => Auth::id() === $item->causer_id
                                        ? __("(You) $item->description")
                                        : $item->description,
                    'created_at' => Carbon::parse($item->created_at)->diffForHumans(),
                ];
            });
    }

    public function render()
    {
        return view('livewire.admin.dashboard.recent-activity-logs');
    }
}
