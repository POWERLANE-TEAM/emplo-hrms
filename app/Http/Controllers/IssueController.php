<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Enums\FilePath;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class IssueController extends Controller
{
    public function index()
    {
        return view('employee.basic.issues.all');
    }

    public function create()
    {
        return view('employee.basic.issues.create');
    }

    public function general()
    {
        return view('employee.hr-manager.relations.issues.all');
    }

    public function review(Issue $issue)
    {
        $issue->loadMissing('reporter');
        return view('employee.hr-manager.relations.issues.review', compact('issue'));
    }

    public function download(string $attachment)
    {
        $path = sprintf('%s/%s', FilePath::ISSUES->value, $attachment);

        return Storage::disk('local')->download($path);
    }

    public function show(Issue $issue)
    {
        return view('employee.basic.issues.show', compact('issue'));
    }

    public function viewAttachment(string $attachment)
    {
        $path = sprintf('%s/%s', FilePath::ISSUES->value, $attachment);

        if (Storage::disk('local')->missing($path)) {
            abort(404);
        }

        return Response::make(Storage::disk('local')->get($path), 200, [
            'Content-Type' => Storage::disk('local')->mimeType($path),
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }
}
