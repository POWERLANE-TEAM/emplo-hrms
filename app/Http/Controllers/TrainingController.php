<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index()
    {
        return view('employee.basic.trainings.index');
    }

    public function show(Employee $employee)
    {
        return view('employee.hr-manager.training.records', compact('employee'));
    }

    public function general()
    {
        return view('employee.hr-manager.training.all');
    }
}
