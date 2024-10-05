<?php

namespace App\Http\Controllers\Employee;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $authenticated_user = Auth::guard('employee')->user();
        // dump($authenticated_user);
        $user = User::where('user_id', $authenticated_user->user_id)
            ->with('roles')
            ->first();

        $role_name = $user->roles->pluck('name')->first();

        // dump(UserRole::INTERMEDIATE->value);
        // dd($role_name);

        switch ($role_name) {
            case UserRole::INTERMEDIATE->value:
                // dd($user);
                return view('employee.hr.index');
                break;

                // HR

                // Superviser

                // Employee

        }
    }
}
