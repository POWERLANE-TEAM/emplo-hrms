<?php

namespace App\Http\Controllers;

use App\Models\JobTitle;

class JobTitleController extends Controller
{
    public function index()
    {
        return view('employee.admin.job-title.index');
    }

    public function create()
    {
        return view('employee.admin.job-title.create');
    }

    public function show(JobTitle $jobTitle)
    {
        return view('employee.admin.job-title.show', compact('jobTitle'));
    }
}
