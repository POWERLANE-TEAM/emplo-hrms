<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\Announcement;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class LatestAnnouncements extends Component
{
    use WithPagination;

    /**
     * Use to measure how long "latest" is
     * 
     * @var 
     */
    protected $weekInterval;

    public function mount()
    {
        $this->weekInterval = Carbon::now()->subWeek();
    }

    #[Computed]
    public function announcements()
    {
        return Announcement::where(function ( $query) {
            $query->where('published_at', '>=', $this->weekInterval)
                ->orWhere('modified_at', '>=', $this->weekInterval);
        })
            ->latest()
            ->with('offices')
            ->simplePaginate(3)
            ->through(function ($item) {
                return (object) [
                    'id' => $item->announcement_id,
                    'title' => $item->announcement_title,
                    'description' => $item->announcement_description,
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
