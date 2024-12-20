<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

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
    public function authorize()
    {
        return view('employee.supervisor.requests.overtime.all');
    }

    /**
     * For secondary approvers (hr staff / manager)
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function cutOff()
    {
        return view('employee.overtime-cut-offs');
    }

    public function summary()
    {
        return view('employee.supervisor.overtime.summary-forms.all');
    }

    public function employeeSummary(Employee $employee, Request $request)
    {
        $query = $request->query('table-filters');
        $filter = $query['payroll'];

        return view('employee.supervisor.overtime.summary-forms.employee',
            compact('employee', 'filter') 
        );
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
    public function archive(Request $request)
    {   
        $query = $request->query('table-filters');
        $filter = $query['payroll'];

        return view('employee.basic.overtime.index', 
            compact('filter')
        );
    }
}
