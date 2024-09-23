<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke($user_with_role_and_account)
    {
        return view('employee.hr.index', ['user' => $user_with_role_and_account]);
    }
}
