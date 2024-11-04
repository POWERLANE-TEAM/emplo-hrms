<?php

namespace App\Http\Controllers\Employee;

use App\Enums\UserPermission;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $guard = Auth::guard();

        $user = $guard->user();

        $dashboard = match (true) {
            $user->hasPermissionTo(UserPermission::VIEW_HR_MANAGER_DASHBOARD->value) => 'employee.hr-manager.index',
            $user->hasPermissionTo(UserPermission::VIEW_EMPLOYEE_DASHBOARD->value) => 'employee.supervisor.index',
            $user->hasPermissionTo(UserPermission::VIEW_EMPLOYEE_DASHBOARD->value) => 'employee.basic.index',

            default => abort(403, 'Unauthorized')
        };

        return view($dashboard);
    }
}
