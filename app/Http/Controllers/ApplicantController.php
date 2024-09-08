<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicantController extends Controller
{

    public function index($page = null)
    {

        if (empty($page) || $page == 'index') {
            return view('applicant/index');
        } else {
            dd($page);
        }
    }
}
