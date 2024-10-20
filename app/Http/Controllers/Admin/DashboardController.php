<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountType;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $guard = Auth::guard('admin');

        $authenticated_user_with_role = Cache::remember('user_' . $guard->id(), 2, function () use ($guard) {
            return User::where('user_id', $guard->id())
                ->with('roles')
                ->first();
        });

        $is_admin = $authenticated_user_with_role->account_type == AccountType::EMPLOYEE->value && $authenticated_user_with_role->hasRole(UserRole::ADVANCED->value);

        if (! $is_admin) {
            abort(403);
        }

        return view('employee.admin.index');
    }
}
