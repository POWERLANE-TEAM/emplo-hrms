<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Enums\EmploymentStatus;
use App\Models\RegularPerformance;
use Illuminate\Support\Facades\Auth;
use App\Models\ProbationaryPerformance;

class PerformanceController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('account.status');
        if ($user->account->status->emp_status_name === EmploymentStatus::PROBATIONARY->label()) {
            return to_route('employee.performances.probationary');
        } else {
            return to_route('employee.performances.regular');
        }
    }

    public function asRegular()
    {
        return view('employee.performance.eval.regular.index');
    }

    public function asProbationary()
    {
        return view('employee.performance.eval.probationary.index');
    }

    public function showAsRegular(RegularPerformance $performance)
    {
        return view('employee.performance.eval.regular.show', compact('performance'));
    }

    public function showAsProbationary(Request $request, Employee $employee)
    {
        $yearPeriod = $request->query('year_period');
        return view('employee.performance.eval.probationary.show', compact('employee', 'yearPeriod'));
    }
}
