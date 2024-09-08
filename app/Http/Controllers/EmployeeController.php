<?php

namespace App\Http\Controllers;

class EmployeeController extends Controller
{
    // Pchange nlng name wla ako maissip
    public function employee($page = null)
    {

        if (empty($page)) {
            return view('employee/index');
        } else {
            dd($page);
        }
    }
}
