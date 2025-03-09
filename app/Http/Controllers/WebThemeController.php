<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebThemeController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'themePreference' => 'required|string|in:light,dark',
        ]);

        $themePreference = $request->input('themePreference');

        session(['themePreference' => $themePreference]);
    }
}
