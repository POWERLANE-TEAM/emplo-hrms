<?php

namespace App\Http\Controllers;

use App\Enums\UserPermission;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /* Show all resource */
    public function index(): ViewFactory|View
    {
        return view();
    }


    /* Show form page for creating resource */
    public function create(): ViewFactory|View
    {
        return view('employee.basic.leaves.request');
    }

    /* store a new resource */
    public function store()
    {
        //
    }

    /* Get single resource */
    public function show(): ViewFactory|View
    {
        return view('employee.basic.leaves.view');
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
