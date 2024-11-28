<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;

class InfoCards extends Component
{
    #[Locked]
    public $totalUsers;

    #[Locked]
    public $activeUsers;

    #[Locked]
    public $recentLogins;

    #[Locked]
    public $loginInterval;

    public function mount()
    {
        $this->loginInterval = Carbon::now()->subDay()->unix();

        $this->totalUsers = User::count();

        $this->activeUsers = User::whereNotNull('email_verified_at')->count();

        $this->recentLogins = DB::table('sessions')->where('last_activity', '>=', $this->loginInterval)->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.info-cards');
    }
}
