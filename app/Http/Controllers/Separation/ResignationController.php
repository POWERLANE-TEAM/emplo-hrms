<?php

namespace App\Http\Controllers\Separation;

use App\Enums\AccountType;
use App\Enums\FilePath;
use App\Enums\ResignationStatus;
use App\Events\ResignationApproved;
use App\Http\Helpers\RouteHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDoc;
use App\Models\Resignation;
use Illuminate\Http\Request;

class ResignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.separation.basic.file-resignation');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request|array $request, $isValidated = false)
    {
        try {
            if (! $isValidated) {
                $request = request();

                $resignationLetter = $request->file('resignationFile');
            }

            if (is_array($request)) {
                $resignationLetter = $request['resignationFile'];
            }

            $hashedName = $resignationLetter->hashName();


            $user = auth()->user();


            if ($user->account_type != AccountType::EMPLOYEE->value) {
                abort(403);
            }

            if ($user->account->documents()->where('file_path', 'like', '%' . FilePath::RESIGNATION->value . '%')->exists()) {
                abort(400, 'Resignation letter already exists.');
            }

            $path = $resignationLetter->storeAs(FilePath::RESIGNATION->value, $hashedName, 'public');


            $employeeDoc = EmployeeDoc::create([
                'employee_id' => auth()->user()->account->employee_id,
                'file_path' => $path,
            ]);

            $resignation =  $employeeDoc->resignation()->create([
                'emp_resignation_doc_id' => $employeeDoc->emp_doc_id,
                'resignation_status_id' => ResignationStatus::PENDING->value,
            ]);

            if (!$isValidated) {
                return redirect()->route('employee.separation.index');
            } else {
                return $resignation;
            }
        } catch (\Throwable $th) {
            if (app()->environment('local')) {
                throw $th;
            } else {
                report($th);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $resignation)
    {

        $resignation = RouteHelper::validateModel(Resignation::class, $resignation);

        if (true) {
            return view('employee.separation.resignation.review', ['resignation' => $resignation]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($request, bool $validated = false)
    {

        $resignationId = when(is_array($request), $request['resignation_id']);
        $resignation = RouteHelper::validateModel(Resignation::class, $resignationId);

        if (!$validated) {
            // $validated = $request->validate([
            //     'resignation_status_id' => 'required|integer',
            //     'initial_approver_comments' => 'required|string',
            // ]);
        }

        // add authorization
        if (!auth()->user()->is($resignation->resignee) && true) {

            if (is_array($request) && $validated) {
                $data = [
                    'resignation_status_id' => $request['resignation_status_id'],
                    'initial_approver' => auth()->user()->account->employee_id,
                    'initial_approver_comments' => $request['initial_approver_comments'],
                ];
            }

        }else{
            if (is_array($request) && $validated && true) {
                $data = [
                    'retracted_at' => $request['retracted_at'],
                ];
            }
        }

        $resignation->update($data);

        if($request['resignation_status_id'] == ResignationStatus::APPROVED->value){
            ResignationApproved::dispatch($resignation->resignee);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
