<?php

namespace App\Http\Controllers;

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
        return view('.employee.pre-employment');
    }

    /* store a new resource */
    public function store(Request $request)
    {
        $prefix = 'pre_emp_doc';

        if ($request->hasFile($prefix)) {

            // Authenticate

            // Validate

            $file = $request->file('pre_emp_doc');
            $file_name = $file->getClientOriginalName();
            echo $file_name;
            $hashedName =  $prefix . '_' . $file->hashName();
            echo $hashedName;

            $doc_id = $request->input('doc_id');
            echo $doc_id;

            // Create a new EmployeeDoc record
            $doc_id = $request->input('doc_id');
            echo $doc_id;

            $user = Auth::user();
            $account_id = $user->account_id;

            $path = $file->storeAs('uploads', $hashedName, 'public'); /* Store in file://storage/app/public/uploads/ */

            $employeeDoc = new EmployeeDoc();
            $employeeDoc->document_id = $doc_id;
            $employeeDoc->employee_id = $account_id;
            $employeeDoc->file = $path;
            $employeeDoc->save();

            //Retrieve the document name from the documents table
            $document = Document::find($doc_id);
            $document_name = $document->name;
            echo $document_name;

            // Return response

            // Optionally, save the path in the database
            // File::create(['path' => $path]);

            // return back()->with('success', 'File uploaded successfully!');
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
