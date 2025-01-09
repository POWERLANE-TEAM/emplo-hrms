<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class ProbationaryPerformanceController extends Controller
{
    public function index()
    {
        return view('employee.supervisor.performance-evaluations.probationaries.all');
    }

    public function create(Employee $employee)
    {
        return view('employee.supervisor.performance-evaluations.probationaries.create', compact('employee'));
    }

    public function show(Request $request, Employee $employee)
    {
        $yearPeriod = $request->query('year_period');
        return view('employee.supervisor.performance-evaluations.probationaries.show', compact('employee', 'yearPeriod'));
    }

    public function review(Request $request, Employee $employee)
    {
        $yearPeriod = $request->query('year_period');
        return view('employee.hr-manager.evaluations.probationary.show', compact('employee', 'yearPeriod'));
    }

    public function general()
    {
        return view('employee.hr-manager.evaluations.probationary.all');
    }
}
