<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\User;
use Livewire\Component;

class InfoCards extends Component
{
    public function render()
    {
        $count = User::selectRaw(
            'COUNT(*) AS total,
            SUM(CASE WHEN email_verified_at IS NOT NULL THEN 1 ELSE 0 END) AS active'
        )->first();

        return view('livewire.admin.dashboard.info-cards', [
            'activeUsersCount' => $count->active,
            'totalUsersCount' => $count->total,
        ]);
    }
}
