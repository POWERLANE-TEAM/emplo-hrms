<?php

namespace App\Http\Controllers;
use App\Enums\UserPermission;
use App\Models\Employee;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;

class AttendanceController extends Controller
{
    /* Show all resource */
    public function index(string $range): ViewFactory|View
    {
if ($range == 'daily') {
    return view('employee.attendance.daily');
        }else{
            return view('employee.attendance.tracking');
        }

    }

    /* Show form page for creating resource */
    // public function create() : ViewFactory|View
    // {
    //     // return view();
    // }

    /* store a new resource */
    public function store()
    {
        //
    }

    /* Get single resource */
    public function show(Employee $employee = null): ViewFactory|View
    {

        if (!auth()->user()->hasPermissionTo(UserPermission::VIEW_ALL_DAILY_ATTENDANCE->value)) {
            abort(403); // Forbidden
        }
        
        if(empty($employee)){
            $employee = auth()->user()->account;
        }

        return view('employee.attendance.work-log', ['employee' => $employee]);
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
