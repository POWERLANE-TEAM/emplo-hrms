<?php

namespace App\Http\Controllers;

use App\Models\CompanyDoc;
use App\Models\Document;
use App\Models\EmployeeDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PreEmploymentController extends Controller
{
    /* Show all resource */
    public function index($page = null)
    {
        //
    }

    /* Show form page for creating resource */
    public function create()
    {
        return view('employee.pre-employment');
    }

    /* store a new resource */
    public function store(Request $request)
    {
        $prefix = 'pre_emp_doc';

        if ($request->hasFile($prefix)) {

            // Authenticate

            // Validate

            // Get user account

            // Make Folder for user

            $file = $request->file('pre_emp_doc');
            $file_name = $file->getClientOriginalName();
            echo $file_name;
            $hashedName = $prefix . '_' . $file->hashName();
            echo $hashedName;

            // Get application id

            $doc_id = $request->input('doc_id');
            echo $doc_id;

            $user = Auth::user();
            $account_id = $user->account_id;

            /* Need to save on folder of user*/
            $path = $file->storeAs('uploads', $hashedName, 'public'); /* Store in file://storage/app/public/uploads/ */

            /* Needs to be updated to store in application docs instead */

            $employeeDoc = new EmployeeDoc();
            $employeeDoc->emp_doc_id = $doc_id;
            $employeeDoc->employee_id = $account_id;
            $employeeDoc->file_path = $path;
            $employeeDoc->save();

            /* Should be preemp_requirements name instead */
            $document = CompanyDoc::find($doc_id);
            $document_name = $document->name;
            echo $document_name;

            // return back()->with('success', '$document_name uploaded successfully!');
        }
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
