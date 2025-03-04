<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\RegularPerformance;
use App\Traits\EmployeePipAiAuth;

class RegularPerformanceController extends Controller
{
    use EmployeePipAiAuth;

    public function index()
    {
        return view('employee.supervisor.performance-evaluations.regulars.all');
    }

    public function create(Employee $employee)
    {
        return view('employee.supervisor.performance-evaluations.regulars.create', compact('employee'));
    }

    public function show(RegularPerformance $performance)
    {
        $performance->loadMissing(['employeeEvaluatee', 'employeeEvaluatee.jobTitle']);
        return view('employee.supervisor.performance-evaluations.regulars.show', compact('performance'));
    }

    public function general()
    {
        return view('employee.hr-manager.evaluations.regular.all');
    }

    public function review(RegularPerformance $performance)
    {
        $performance->loadMissing(['employeeEvaluatee', 'employeeEvaluatee.jobTitle']);
        $isEndpointAccessible = $this->isEndpointAccessible();
        return view('employee.hr-manager.evaluations.regular.show',  compact('performance', 'isEndpointAccessible'));
    }
}
