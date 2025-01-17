<?php

namespace App\Jobs\Guest;

use App\Events\Guest\ResumeParsed;
use App\Http\Controllers\DocumentController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ParseResumeJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $resumePath, protected $authId)
    {
        $this->resumePath = $resumePath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $resumeParser = new DocumentController;

            $resumeFile = new \Illuminate\Http\UploadedFile(
                $this->resumePath,
                basename($this->resumePath),
                null,
                null,
                true
            );

            $parsedResume = $resumeParser->recognizeText($resumeFile, 'array');

            if (! empty($parsedResume)) {

                // Broadcast event
                ResumeParsed::dispatch($parsedResume, $this->authId);
            }
        } catch (\Throwable $th) {

            report('Parsing resume error: '.$th->getMessage());
        }
    }
}
