<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function contracts()
    {
        //
    }

    public function preEmployments()
    {
        return view('employee.basic.documents.pre-employments.all', [
            'employee' => auth()->user()->account
        ]);
    }

    public function trainings()
    {
        //
    }

    public function payslips()
    {
        //
    }

    public function incidents()
    {
        //
    }

    public function issues()
    {
        return view('employee.basic.documents.issues.index');
    }

    public function leaves()
    {
        abort(418, __('Idk man, sometimes it do be like that.'));
    }
}
