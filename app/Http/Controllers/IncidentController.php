<?php

namespace App\Http\Controllers;

use App\Enums\FilePath;
use App\Models\Incident;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class IncidentController extends Controller
{
    public function index()
    {
        return view('employee.hr-manager.relations.incidents.all');
    }

    public function create()
    {
        return view('employee.hr-manager.relations.incidents.create');
    }

    public function show(Incident $incident)
    {
        return view('employee.hr-manager.relations.incidents.show', compact('incident'));
    }

    public function download(string $attachment)
    {
        return Storage::disk('local')->download(FilePath::INCIDENTS->value.'/'.$attachment);
    }

    public function viewAttachment(string $attachment)
    {
        $path = FilePath::INCIDENTS->value.'/'.$attachment;

        if (Storage::disk('local')->missing($path)) {
            abort(404);
        }

        return Response::make(Storage::disk('local')->get($path), 200, [
            'Content-Type' => Storage::disk('local')->mimeType($path),
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }
}
