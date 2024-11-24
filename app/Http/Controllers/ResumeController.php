<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\ProcessRequest;
use Google\Cloud\DocumentAI\V1\RawDocument;


class ResumeController extends Controller
{
    public function processResume(Request $request)
    {

        // Validate file upload
        $validated = $request->validate([
            'resume' => 'required|file|mimes:pdf|max:3072', // Accept only PDF files, max 3MB
        ]);

        // Add anti virus check here found free here https://github.com/sunspikes/clamav-validator

        $credentialsPath = storage_path('app/services/credentials/emplo-ocr-5fa22df3c01d.json');

        $credentials = json_decode(file_get_contents($credentialsPath), true);

        $projectId = $credentials['project_id'];

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);
        putenv("GOOGLE_CLOUD_PROJECT=$projectId");

        $processorId = env('GOOGLE_DOCUMENT_AI_PROCESSOR_ID', '');
        $processorVersion = env('GOOGLE_DOCUMENT_AI_PROCESSOR_VER', '');

        $client = new DocumentProcessorServiceClient();

        Log::info('credentials', ['client' => $credentialsPath]);
        Log::info('credentials', ['credentials' => $credentials]);
        Log::info('CLient', ['client' => $client]);

        try {
            // Save uploaded file to storage
            $file = $request->file('resume');
            $path = $file->store('resumes'); // Save to storage/app/resumes
            $filePath = Storage::path($path);

            $fileContent = file_get_contents($filePath);

            $name = $client->processorVersionName(
                $projectId,
                'us', // Location United State server
                $processorId,
                $processorVersion
            );

            // Prepare the request payload for Document AI
            $rawDocument = new RawDocument([
                'content' => $fileContent,
                'mime_type' => 'application/pdf',
            ]);


            $request = new ProcessRequest([
                'name' => $name,
            ]);

            $request->setSkipHumanReview(true);
            $request->setRawDocument($rawDocument);


            Log::info('Request human', ['text' => $request->getSkipHumanReview()]);
            Log::info('Request name', ['text' => $request->getName()]);
            Log::info('Request document', ['text' => $request->getRawDocument()]);
            Log::info('Request document', ['has' => $request->hasRawDocument()]);

            $response = $client->processDocument($request);

            Log::info('Response ', ['response' => $response]);

            $document = $response->getDocument();
            $documentText = $document->getText();
            $documentContent = $document->getContent();
            $documentChanges = $document->getTextChanges();
            $documentShard = $document->getShardInfo();
            $hasError = $document->hasError();
            $getDocumentLayout = $document->getDocumentLayout();
            $getSource = $document->getSource();

            $entities = $document->getEntities();

            $extractedData = [];

            // Get Detected Resume Fields
            foreach ($entities as $entity) {
                $type = $entity->getType();
                $mentionText = $entity->getMentionText();
                $confidence = $entity->getConfidence();

                // Store to array field_name => ocr_value
                $extractedData[$type] = $mentionText;

                Log::info('Entity', [
                    'type' => $type,
                    'mentionText' => $mentionText,
                    'confidence' => $confidence,
                ]);
            }

            Log::info('Document', ['document' => $document]);
            Log::info('Document Text', ['Text' => $documentText]);
            Log::info('Document documentContent', ['documentContent' => $documentContent]);
            Log::info('Document documentShard', ['documentShard' => $documentShard]);
            Log::info('Document has error', ['hasError' => $hasError]);
            Log::info('Document has getDocumentLayout', ['getDocumentLayout' => $getDocumentLayout]);
            Log::info('Document has getSource', ['getSource' => $getSource]);

            // Log additional metadata for verification
            Log::info('Document Metadata', [
                'mimeType' => $document->getMimeType(),
                'entities' => $document->getEntities(),
                'entitiesRel' => $document->getEntityRelations(),
                'pages' => $document->getPages(),
            ]);

            // Extract data based on schema (custom parsing logic)
            Log::info('Response', ['response' => $response]);
            Log::info('Response parse', ['response' => $extractedData]);


            // Clean up the uploaded file
            Storage::delete($path);

            return response()->json([
                'status' => 'success',
                'parsedData' => $extractedData,
            ]);
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Extract field from document text using a simple search mechanism.
     */
    private function extractField($text, $fieldName)
    {
        // Simple regex pattern to find "Field Name: Value"
        $pattern = "/$fieldName:\s*(.+)/i";
        if (preg_match($pattern, $text, $matches)) {
            return trim($matches[1]);
        }
        return null; // Return null if the field is not found
    }

    /**
     * Get Google Cloud access token dynamically.
     */
    private function getAccessToken()
    {
        $keyFilePath = storage_path('app/services/credentials/emplo-ocr-6d9eff441e8b.json');
        $credentials = json_decode(file_get_contents($keyFilePath), true);

        $tokenUrl = 'https://oauth2.googleapis.com/token';
        $response = Http::asForm()->post($tokenUrl, [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $this->createJwtAssertion($credentials),
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to obtain Google Cloud access token');
        }

        return $response->json()['access_token'];
    }

    /**
     * Create a JWT assertion for the Google Cloud service account.
     */
    private function createJwtAssertion($credentials)
    {
        $now = time();
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $payload = [
            'iss' => 'c.ivanbandilla@gmail.com',
            'scope' => 'https://www.googleapis.com/auth/cloud-platform',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ];

        $base64Header = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $base64Payload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
        $signatureInput = $base64Header . '.' . $base64Payload;

        $privateKey = $credentials['private_key'];
        openssl_sign($signatureInput, $signature, $privateKey, 'sha256');

        return $signatureInput . '.' . rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
    }
}
