<?php

namespace App\Http\Controllers;

use App\Enums\StatusBadge;
use Illuminate\Http\Request;
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
        $status = match (true) {
            ! is_null($leave->denied_at) => StatusBadge::DENIED,
            ! is_null($leave->fourth_approver_signed_at) => StatusBadge::APPROVED,
            default => StatusBadge::FOR_APPROVAL,
        };

        return view('employee.basic.leaves.view', compact('leave', 'status'));
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