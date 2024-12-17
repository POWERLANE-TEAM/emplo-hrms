<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebThemeController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'themePreference' => 'required|string|in:light,dark'
        ]);

        $themePreference = $request->input('themePreference');

        session(['themePreference' => $themePreference]);
    }
}
