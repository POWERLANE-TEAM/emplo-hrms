<?php

namespace App\Http\Controllers\Employee;

use App\Enums\UserPermission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        $dashboard = match (true) {
            $user->hasPermissionTo(UserPermission::VIEW_HR_MANAGER_DASHBOARD->value) => 'employee.hr-manager.index',
            $user->hasPermissionTo(UserPermission::VIEW_EMPLOYEE_DASHBOARD->value) => 'employee.basic.index',
            $user->hasPermissionTo(UserPermission::VIEW_EMPLOYEE_DASHBOARD->value) => 'employee.supervisor.index',

            default => abort(403, 'Unauthorized')
        };

        return view($dashboard);
    }
}
