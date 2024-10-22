<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $guard = Auth::guard('admin');

        Cache::flexible('user_' . $guard->id(), [25, 40], function () use ($guard) {
            return User::where('user_id', $guard->id())
                ->with(['roles', 'account'])
                ->get()
                ->first();
        });

        return view('employee.admin.index');
    }
}
