<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegularPerformance;
use App\Models\ProbationaryPerformance;

class PerformanceController extends Controller
{
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

    public function showAsProbationary(ProbationaryPerformance $performance)
    {
        return view('employee.performance.eval.probationary.show', compact('performance'));
    }
}
