<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicantController extends Controller
{

    public function apply($page = null)
    {

        if (empty($page)) {
            return view('applicants/apply/index');
        } else {
            dd($page);
        }
    }
}
