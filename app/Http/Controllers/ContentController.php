<?php

namespace App\Http\Controllers;

class ContentController extends Controller
{
    // Informational Modal Content
    public function getModalContent($modalKey)
    {
        $path = resource_path('json/modals-content.json');
        $content = json_decode(file_get_contents($path), true);

        return response()->json($content[$modalKey] ?? ['error' => 'Content not found']);
    }

    // Help Center Content
}
