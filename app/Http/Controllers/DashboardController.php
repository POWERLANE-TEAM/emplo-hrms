<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /* Show all resource */
    public function index($prefix)
    {

        $authenticated_user = Auth::user();

        $user_with_role_and_account = User::with(['role', 'account'])
            ->where('user_id', $authenticated_user->user_id)
            ->first();

        redirect()->to($prefix . '/');

        switch ($user_with_role_and_account->role->user_role_name) {
            case 'HR MANAGER':
                return view('employee.hr.index', ['user' => $user_with_role_and_account]);
            case 'SYSADMIN':

                return view('employee.head-admin.index', ['user' => $user_with_role_and_account]);
            case 'USER':
                return;
            default:
                return view('employee.standard.index', ['user' => $user_with_role_and_account]);
        }
    }

    /* Show form page for creating resource */
    public function create()
    {
        //
    }

    /* store a new resource */
    public function store(Request $request)
    {
        //
    }

    /* Get single resource */
    public function show()
    {
        //
    }

    /* Patch or edit */
    public function update()
    {
        //
    }

    /* Delete */
    public function destroy()
    {
        //
    }
}
