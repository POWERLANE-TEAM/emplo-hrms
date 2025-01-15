<?php

namespace App\Livewire\Employee\Dashboard;

use Livewire\Component;
use App\Models\Announcement;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class LatestAnnouncements extends Component
{
    use WithPagination;

    /**
     * Use to measure how long "latest" is
     */
    protected $weekInterval;

    public function mount()
    {
        $this->weekInterval = now()->subWeek();
    }

    #[Computed]
    public function announcements()
    {
        return Announcement::whereHas('offices', function ($query) {
                $query->where('announcement_details.job_family_id', 
                    Auth::user()->account->jobTitle->jobFamily->job_family_id
                );
            })
            ->where(function ($query) {
                $query->where('published_at', '>=', $this->weekInterval)
                    ->orWhere('modified_at', '>=', $this->weekInterval);
            })
            ->latest()
            ->with('publisher')
            ->simplePaginate(3)
            ->through(function ($item) {
                return (object) [
                    'id' => $item->announcement_id,
                    'title' => $item->announcement_title,
                    'description' => $item->announcement_description,
                    'published_at' => $item->published_at->diffForHumans(),
                    'modified_at' => $item->modified_at->diffForHumans(),
                    'publisher' => $item->publisher->first_name,
                ];
            });
    }

    public function render()
    {
        return view('livewire.employee.dashboard.latest-announcements');
    }
}
