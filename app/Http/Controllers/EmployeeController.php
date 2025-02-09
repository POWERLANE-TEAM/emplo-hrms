<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employee.hr-manager.employees.all');
    }

    public function show(Employee $employee)
    {
        return view('employee.hr-manager.employees.information', compact('employee'));
    }
}
