<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index()
    {
        return view('employee.hr-manager.training.all');
    }

    public function show(Employee $employee)
    {
        return view('employee.hr-manager.training.records', compact('employee'));
    }
}
