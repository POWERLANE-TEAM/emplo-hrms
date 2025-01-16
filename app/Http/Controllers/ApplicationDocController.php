<?php

namespace App\Http\Controllers;

use App\Enums\AccountType;
use App\Enums\FilePath;
use App\Models\Application;
use App\Models\ApplicationDoc;
use App\Models\PreempRequirement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationDocController extends Controller
{
    /* Show all resource */
    public function index($page = null)
    {
        //
    }

    /* Show form page for creating resource */
    public function create($isCopy = false)
    {
        if ($isCopy) {
            return view('employee.pre-employment-copy');
        }

        if(!auth()->user()->account_type ==  AccountType::APPLICANT->value)
            return redirect()->route('employee.general.documents.all');

        return view('employee.pre-employment');

    }

    /* store a new resource */
    public function store(Request $request)
    {
        $prefix = 'applicationDoc';
        $prefix = $request->hasFile('resumeFile') ? 'resumeFile' : $prefix;

        if ($request->hasFile($prefix)) {

            // Authenticate

            // Validate
            // Validation should include malware scan found free here https://github.com/sunspikes/clamav-validator
            // This need a third party clam anti virus (https://docs.clamav.net/) but I think this should be done in docker or other containerized environment
            // As it may conflict with the default av on the dev machine

            // Get user accounts

            // Make Folder for user

            $file = $request->file($prefix);
            $file_name = $file->getClientOriginalName();
            echo $file_name;

            // Get application id

            $doc_id = $request->input('doc_id');
            echo $doc_id;

            $hashed_name = $prefix . '_' . dechex($doc_id) . '_' . $file->hashName();
            echo $hashed_name;

            $user = Auth::user();

            $user_id = $user->user_id;
            // dump($user);

            $preemployed_user = User::with('account.application')->find($user_id);

            dd($preemployed_user);

            $first_name = $preemployed_user->account->first_name;
            $last_name = $preemployed_user->account->last_name;

            $user_folder = $first_name . '_' . $last_name . '_' . dechex($user_id);

            $application_id = $preemployed_user->account->application->application_id;

            $path = $file->storeAs(FilePath::PRE_EMPLOYMENT->value . "$user_folder", $hashed_name, 'public');

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
