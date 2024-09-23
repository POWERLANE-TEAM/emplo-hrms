<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $authenticated_user = Auth::guard('admin')->user();
        // dump($authenticated_user);
        $user_with_role_and_account = User::with(['role', 'account'])
            ->where('user_id', $authenticated_user->user_id)
            ->first();

        return view('employee.admin.index', ['user' => $user_with_role_and_account]);
    }
}
