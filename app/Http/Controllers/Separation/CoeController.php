<?php

namespace App\Http\Controllers\Separation;

use App\Enums\EmploymentStatus;
use App\Enums\FilePath;
use App\Http\Controllers\Controller;
use App\Http\Helpers\RouteHelper;
use App\Models\CoeRequest;
use App\Models\EmployeeDoc;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CoeController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $coe)
    {

        $coe = RouteHelper::validateModel(CoeRequest::class, $coe);


        return view('employee.separation.coe.request', compact('coe'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($coeRequest)
    {

        // dump($coeRequest);
        $coeData = [
            'name' =>  $coeRequest->requestor->fullname,
            'empStart' => $coeRequest->requestor->lifecycle->start_date,
            'empEnd' =>  $coeRequest->requestor->lifecycle->separated_at,
            'jobTitle'  => $coeRequest->requestor->jobTitle->department->department_name,
            'jobDepartment'  => $coeRequest->requestor->jobTitle->department->department_name,
            'issuedDate'  => now(),
            'hrManager' =>  auth()->user()->account->fullname,
            'companyAddr' => 'Rowsuz Business Center, Diversin Rd',
        ];

        Pdf::setOption(['dpi' => 300]);
        $coe = Pdf::loadView('coe', $coeData);
        $coe->setPaper('a4', 'landscape');
        $coePdf = $coe->output();

        $relativePath = FilePath::COE->value . hash('sha256', time()) . '.pdf';
        $coePath = 'public/' . $relativePath;

        Storage::put($coePath, $coePdf);

        return $relativePath;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($coe, $coePath)
    {

        DB::transaction(function () use ($coe, $coePath) {
            $employeeDoc = EmployeeDoc::create([
                'employee_id' => $coe->requestor->employee_id,
                'file_path' => $coePath
            ]);

            $coe->update([
                'generated_by' => auth()->user()->account->employee_id,
                'coe_path' => $coePath,
                'emp_coe_doc_id' => $employeeDoc->emp_doc_id,
            ]);

            $coe->requestor->jobDetail->update([
                'emp_status_id' => EmploymentStatus::RESIGNED->value
            ]);

            $coe->requestor->lifecycle->update([
                'separated_at' => now(),
            ]);
        });

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
