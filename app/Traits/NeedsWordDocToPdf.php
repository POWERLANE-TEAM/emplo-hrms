<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

trait NeedsWordDocToPdf
{
    public function convert($docxFilePath, $outputDir, $disk = 'public')
    {
        if (!Storage::disk($disk)->exists($outputDir)) {
            Storage::disk($disk)->makeDirectory($outputDir, 0777, true);
        }

        if (!Storage::disk($disk)->exists($docxFilePath)) {
            throw new \Exception('DOCX file does not exist: ' . $docxFilePath);
        }

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $execName = '"C:\Program Files\LibreOffice\program\soffice.exe"';
        } else {
            $execName = '/usr/bin/libreoffice';
        }

        Log::info(strtoupper(substr(PHP_OS, 0, 3)));

        $pdfFilePath = $outputDir . '/' . pathinfo($docxFilePath, PATHINFO_FILENAME) . '.pdf';

        $command = $execName . ' --headless --convert-to pdf --outdir ' . escapeshellarg(Storage::disk($disk)->path($outputDir)) . ' ' . escapeshellarg(Storage::disk($disk)->path($docxFilePath));

        Log::info('Executing command: ' . $command);

        exec($command, $output, $returnVar);

        // Log the output for debugging
        Log::info('Command output: ' . implode("\n", $output));
        Log::error('Command error output: ' . $returnVar);


        if ($returnVar === 0) {

            if (Storage::disk($disk)->exists($pdfFilePath)) {
                return $pdfFilePath;
            } else {
                throw new \Exception('PDF file was not created: ' . $pdfFilePath);
            }
        }

        throw new \Exception('Failed to convert DOCX to PDF: ' . implode("\n", $output));
    }
}
