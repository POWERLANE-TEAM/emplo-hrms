<?php

namespace App\Http\Controllers\Employee;

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
        $user_with_role_and_account = User::with(['role', 'account'])
            ->where('user_id', $authenticated_user->user_id)
            ->first();

        // dd($user_with_role_and_account);
        switch ($user_with_role_and_account->role->user_role_name) {
            case 'HR MANAGER':
                return view('employee.hr.index', ['user' => $user_with_role_and_account]);

                // HR

                // Superviser

                // Employee

        }
    }
}
