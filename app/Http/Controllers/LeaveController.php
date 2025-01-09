<?php

namespace App\Http\Controllers;

use App\Models\EmployeeLeave;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employee.basic.leaves.all');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.basic.leaves.request');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeLeave $leave)
    {
        return view('employee.basic.leaves.view', compact('leave'));
    }

    public function myBalance()
    {
        return view('employee.basic.leaves.balance');
    }

    public function subordinateBalance()
    {
        return view('employee.supervisor.requests.leaves.balance');
    }

    public function generalBalance()
    {
        return view('employee.hr-manager.leaves.balance');
    }

    public function overview()
    {
        return view('employee.basic.leaves.overview');
    }

    public function request()
    {
        return view('employee.supervisor.requests.leaves.all');
    }

    public function general()
    {
        return view('employee.hr-manager.leaves.all');
    }
}