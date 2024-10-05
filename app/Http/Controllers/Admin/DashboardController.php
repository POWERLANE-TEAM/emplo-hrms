<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {

        $authenticated_user = Auth::guard('admin')->user();

        $user_with_role_and_account = User::where('user_id', $authenticated_user->user_id)
            ->with(['roles'])
            ->first();

        $is_admin = $authenticated_user->account_type == 'employee' && $user_with_role_and_account->hasRole(UserRole::ADVANCED->value);

        if (!$is_admin) {
            return abort(403);
        }

        return view('employee.admin.index');
    }
}
