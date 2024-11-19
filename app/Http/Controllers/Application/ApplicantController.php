<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;

class ApplicantController extends Controller
{
    /* Show all resource */
    public function index($page = null)
    {
        if (empty($page) || $page == 'index') {
            return view('applicant/index');
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
    public function show()
    {
        //
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
