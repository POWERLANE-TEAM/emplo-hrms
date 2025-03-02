<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function index()
    {
        return view('employee.profile.information.index');
    }

    public function edit()
    {
        return view('employee.profile.information.edit');
    }

    public function settings()
    {
        return view('employee.profile.settings');
    }

    public function logs()
    {
        return view('employee.profile.activity-logs');
    }
}
