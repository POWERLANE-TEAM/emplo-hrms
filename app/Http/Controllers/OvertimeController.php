<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    /* Show all resource */
    public function index(): ViewFactory|View
    {
        return view('employee.basic.overtime.all-summary-forms');
    }


    /* Show form page for creating resource */
    public function create(): ViewFactory|View
    {
        return view('employee.basic.overtime.requests');
    }

    /* store a new resource */
    public function store()
    {
        //
    }

    /* Get single resource */
    public function show(Employee $employee, $view): ViewFactory|View
    {
        dd($employee);

        if ($view == 'summary')
            return view('employee.basic.overtime.summary-form', ['view' => $view]);


        return view('employee.basic.overtime.requests', ['view' => $view]);
    }

    /* Patch or edit */
    public function update()
    {
        //
    }

    /* Delete */
    public function destroy()
    {
        //
    }
}
