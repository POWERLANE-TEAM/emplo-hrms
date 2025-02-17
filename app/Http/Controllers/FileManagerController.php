<?php

namespace App\Http\Controllers;

use App\Enums\FilePath;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FileManagerController extends Controller
{
    public function contracts()
    {
        return view('employee.basic.documents.contracts.index');
    }

    public function preEmployments()
    {
        return view('employee.basic.documents.pre-employments.all', [
            'employee' => auth()->user()->account,
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
        return view('employee.basic.documents.leaves.index');
    }

    public function viewContractAttachment(string $attachment)
    {
        $path = sprintf('%s/%s', FilePath::CONTRACTS->value, $attachment);

        if (Storage::disk('local')->missing($path)) {
            abort(404);
        }

        return Response::make(Storage::disk('local')->get($path), 200, [
            'Content-Type' => Storage::disk('local')->mimeType($path),
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }
}
