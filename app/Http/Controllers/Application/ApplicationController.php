<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Livewire\Employee\Applicants\Show;
use App\Models\Application;

class ApplicationController extends Controller
{
    /* Show all resource */
    public function index($page = null)
    {
        if (empty($page) || $page == 'index') {
            return view('employee.application.index');
        }
    }


    /* Show form page for creating resource */
    public function create()
    {
        //
    }

    /* store a new resource */
    public function store()
    {
        //
    }

    /* Get single resource */
    public function show(Application $application)
    {
        return view('employee.application.show', ['application' => $application]);
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
