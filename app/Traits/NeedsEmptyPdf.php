<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;

trait NeedsEmptyPdf
{
    public function emptyPdf(string $filePath, string $fileName, ?string $content = null)
    {
        $filePath = sprintf('%s/%s', $filePath, $fileName);
        $pdf = Pdf::loadHTML($content);

        return [$filePath, $pdf];
    }
}
