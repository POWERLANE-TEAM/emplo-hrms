<?php

namespace App\Http\Controllers\Employee;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $guard = Auth::guard('employee');

        $user = Cache::flexible('user_' . $guard->id(), [30, 60], function () use ($guard) {
            return User::where('user_id', $guard->id())
                ->with(['roles', 'account'])
                ->get()
                ->first();
        });

        $dashboard = match (true) {
            $user->hasRole(UserRole::INTERMEDIATE->value) => 'employee.hr-manager.index',
                // HR

                // Superviser

                // Employee

            default => '',
        };

        return view($dashboard);
    }
}
