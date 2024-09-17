<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        //
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads', 'public'); // Store in 'storage/app/public/uploads'

            // Optionally, save the path in the database
            // File::create(['path' => $path]);

            return back()->with('success', 'File uploaded successfully!');
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
