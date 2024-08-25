<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
