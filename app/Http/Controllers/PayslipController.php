<?php

namespace App\Http\Controllers;

use App\Enums\FilePath;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PayslipController extends Controller
{
    public function index()
    {
        return view('employee.basic.payslips.all');
    }

    public function download(string $attachment)
    {
        $path = sprintf('%s/%s', FilePath::PAYSLIPS->value, $attachment);

        return Storage::disk('local')->download($path);
    }

    public function viewAttachment(string $attachment)
    {
        $path = sprintf('%s/%s', FilePath::PAYSLIPS->value, $attachment);

        if (Storage::disk('local')->missing($path)) {
            abort(404);
        }

        return Response::make(Storage::disk('local')->get($path), 200, [
            'Content-Type' => Storage::disk('local')->mimeType($path),
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }

    public function general()
    {
        return view('employee.hr-manager.payslips.all');
    }

    public function bulkUpload()
    {
        return view('employee.hr-manager.payslips.bulk-upload');
    }
}
