<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Announcement;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class LatestAnnouncements extends Component
{
    use WithPagination;

    /**
     * Use to measure how long "latest" is
     */
    protected $weekInterval;

    public function mount()
    {
        $this->weekInterval = Carbon::now()->subWeek();
    }

    #[Computed]
    public function announcements()
    {
        return Announcement::where(function ($query) {
            $query->where('published_at', '>=', $this->weekInterval)
                ->orWhere('modified_at', '>=', $this->weekInterval);
        })
            ->latest()
            ->with(['offices', 'publisher'])
            ->simplePaginate(3)
            ->through(function ($item) {
                return (object) [
                    'id' => $item->announcement_id,
                    'title' => $item->announcement_title,
                    'description' => $item->announcement_description,
                    'published_at' => $item->published_at->diffForHumans(),
                    'modified_at' => $item->modified_at->diffForHumans(),
                    'publisher' => $item->publisher->first_name,
                    'offices' => $item->offices->map(function ($item) {
                        return (object) [
                            'id' => $item->job_family_name,
                            'name' => $item->job_family_name,
                        ];
                    }),
                ];
            });
    }

    public function render()
    {
        return view('livewire.admin.dashboard.latest-announcements');
    }
}
