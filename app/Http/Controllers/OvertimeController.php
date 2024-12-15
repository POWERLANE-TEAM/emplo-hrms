<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employee.basic.overtime.all');
    }

    /**
     * For initial approvers (supervisors / dept head)
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function initialApprovals()
    {
        return view('employee.supervisor.requests.overtime.all');
    }

    /**
     * For secondary approvers (hr staff / manager)
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function secondaryApprovals()
    {
        return view('employee.hr-manager.overtime.all');
    }

    /**
     * Show recent overtime requests table.
     */
    public function recent()
    {
        return view('employee.basic.overtime.recent-records');
    }

    /**
     * Show archive overtime requests table.
     */
    public function archive()
    {
        return view('employee.basic.overtime.index');
    }
}
