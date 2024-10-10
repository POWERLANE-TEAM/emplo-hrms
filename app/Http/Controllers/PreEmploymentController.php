<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDoc;
use App\Models\PreempRequirement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            // Get application id

            $doc_id = $request->input('doc_id');
            echo $doc_id;

            $hashed_name = $prefix.'_'.dechex($doc_id).'_'.$file->hashName();
            echo $hashed_name;

            $user = Auth::user();

            $user_id = $user->user_id;
            // dump($user);

            $preemployed_user = User::with('account.application')->find($user_id);

            // dd($preemployed_user);

            $first_name = $preemployed_user->account->first_name;
            $last_name = $preemployed_user->account->last_name;

            $user_folder = $first_name.'_'.$last_name.'_'.dechex($user_id);

            $application_id = $preemployed_user->account->application->application_id;

            $path = $file->storeAs("uploads/applicant/applications/pre-emp/$user_folder", $hashed_name, 'public');

            /* Needs to be updated to store in application docs instead */

            $preemp_doc = new ApplicationDoc;
            $preemp_doc->preemp_req_id = $doc_id;
            $preemp_doc->application_id = $application_id;
            $preemp_doc->file_path = $path;
            $preemp_doc->save();

            /* Should be preemp_requirements name instead */
            $document = PreempRequirement::find($doc_id);
            $document_name = $document->preemp_req_name;
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
