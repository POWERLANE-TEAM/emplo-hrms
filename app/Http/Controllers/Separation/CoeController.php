<?php

namespace App\Http\Controllers\Separation;

use App\Enums\EmploymentStatus;
use App\Enums\FilePath;
use App\Http\Controllers\Controller;
use App\Http\Helpers\RouteHelper;
use App\Models\CoeRequest;
use App\Models\EmployeeDoc;
use App\Traits\NeedsWordDocToPdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor as WordTemplateProcessor;

class CoeController extends Controller
{
    use NeedsWordDocToPdf;

    private $coeTemplatePath = FilePath::DOC_TEMPLATE->value.'Certificate of Appreciation.docx';

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

        $coe->loadMissing(['requestor.lifecycle', 'requestor.jobTitle.department']);

        return view('employee.separation.coe.request', compact('coe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($coeRequest)
    {

        $coeRequest->loadMissing(['requestor.lifecycle', 'requestor.jobTitle.department']);

        $coeData = [
            'name' => $coeRequest->requestor->fullname,
            'empStart' => $coeRequest->requestor->lifecycle->start_date,
            'empEnd' => $coeRequest->requestor->lifecycle->separated_at,
            'jobTitle' => $coeRequest->requestor->jobTitle->job_title,
            'jobDepartment' => $coeRequest->requestor->jobTitle->department->department_name,
            'issuedDate' => now(),
            'hrManager' => auth()->user()->account->fullname,
            'companyAddr' => 'Rowsuz Business Center, Diversin Rd',
        ];

        $relativePath = $this->generateContent($coeRequest);

        return $relativePath;
    }

    private function generateContent($coeRequest)
    {
        $reader = IOFactory::createReader('Word2007');

        if (Storage::disk('public')->missing($this->coeTemplatePath)) {
            abort(404);
        }

        $templateProcessor = new WordTemplateProcessor(Storage::disk('public')->path($this->coeTemplatePath));

        $issuedDate = now();

        $issueDay = Carbon::parse($issuedDate)->isoFormat('Do');
        $issueMonth = Carbon::parse($issuedDate)->format('F');
        $issueYear = Carbon::parse($issuedDate)->isoFormat('YYYY');

        $values = [
            // Long Names isnt shrinked
            'EMPLOYEE_NAME' => $coeRequest->requestor->fullname,
            'START_DATE' => $coeRequest->requestor->lifecycle->start_date,
            'END_DATE' => $coeRequest->requestor->lifecycle->separated_at,
            'JOB_TITLE' => $coeRequest->requestor->jobTitle->job_title,
            'DEPT_NAME' => $coeRequest->requestor->jobTitle->department->department_name,
            'ORDINAL' => $issueDay,
            'MONTH' => $issueMonth,
            'YEAR' => $issueYear,
            'COMPANY_ADDRESS' => 'Rowsuz Business Center, Diversin Rd',
            'HRManager_NAME' => auth()->user()->account->fullname,
        ];

        $signature = null;

        if (auth()->user()->account->signature) {
            $signatureData = auth()->user()->account->signature;

            if (is_string($signatureData)) {
                $signature = Storage::disk('public')->path($signatureData);
            } else {
                $signature = $signatureData;
            }
        }
        try {
            $templateProcessor->setImageValue('USER_SIGNATURE', $signature);
        } catch (\Throwable $th) {
            $signature = Storage::disk('public')->path(FilePath::DEFAULT_SIGNATURE->value);
            $templateProcessor->setImageValue('USER_SIGNATURE', $signature);
            report($th);
        }

        $templateProcessor->setValues($values);
        $templateProcessor->setImageValue('USER_SIGNATURE', $signature);

        $docxFilePath = FilePath::COE->value.hash('sha256', time()).'.docx';
        $templateProcessor->saveAs(Storage::disk('public')->path($docxFilePath));

        $disk = 'public';

        try {
            $pdfFilePath = $this->convert($docxFilePath, FilePath::COE->value, $disk);
            Storage::disk('public')->delete($docxFilePath);

            return $pdfFilePath;
        } catch (\Throwable $th) {
            report($th);

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($coe, $coePath)
    {

        DB::transaction(function () use ($coe, $coePath) {
            $employeeDoc = EmployeeDoc::create([
                'employee_id' => $coe->requestor->employee_id,
                'file_path' => $coePath,
            ]);

            $coe->update([
                'generated_by' => auth()->user()->account->employee_id,
                'coe_path' => $coePath,
                'emp_coe_doc_id' => $employeeDoc->emp_doc_id,
            ]);

            $coe->requestor->jobDetail->update([
                'emp_status_id' => EmploymentStatus::RESIGNED->value,
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
