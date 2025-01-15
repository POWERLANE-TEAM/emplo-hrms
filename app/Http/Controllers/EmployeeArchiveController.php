<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class EmployeeArchiveController extends Controller
{
    public function index()
    {
        return view('employee.hr-manager.archive.index');
    }

    public function show(Employee $employee)
    {

        return view('employee.hr-manager.archive.records', compact('employee'));
    }
}
