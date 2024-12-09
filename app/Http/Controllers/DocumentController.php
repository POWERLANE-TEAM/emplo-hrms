<?php

namespace App\Http\Controllers;

use App\Traits\DocumentAIAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\ProcessRequest;
use Google\Cloud\DocumentAI\V1\RawDocument;
use Illuminate\Http\UploadedFile;

class DocumentController extends Controller
{


    use DocumentAIAuth;

    /**
     * Retrieve a file from the request.
     *
     * @return \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|array|null
     */
    public function recognizeText(Request|UploadedFile $fileRequest, string $returnType,  ?string $mimeType = null, ?string $fileEnvelope = null, ?bool $validated = false)/* : UploadedFile */
    {

        if ($fileRequest instanceof Request) {

            if (!$validated) {
                $validated = $fileRequest->validate([
                    '*' => 'required|file',
                ]);
            }

            // Add anti virus check here found free here https://github.com/sunspikes/clamav-validator

            try {
                $file = $fileRequest->file($fileEnvelope);

                $path = $file->store('tmp');
                $filePath = Storage::path($path);
            } catch (\Exception $e) {
                report($e->getMessage());
            }
        } else {

            try {
                $filePath = $fileRequest->getPathname();
            } catch (\Exception $e) {
                report($e->getMessage());
            }
        }

        dump('resume processor');

        $credentials = $this->setCredentials();

        [$processorId, $processorVersion] = $this->getProcessor();

        $client = new DocumentProcessorServiceClient();

        $apiName = $client->processorVersionName(
            $credentials['project_id'],
            config('services.google.document_ai.processor_loc'), // Location United State server is default
            $processorId,
            $processorVersion
        );

        Log::info('CLient', ['client' => $client]);

        // Prepare the request payload for Document AI
        try {

            $fileContent = file_get_contents($filePath);

            $mimeType = $mimeType ?? mime_content_type($filePath);
            $rawDocument = new RawDocument([
                'content' => $fileContent,
                'mime_type' => $mimeType,
            ]);

            $apiRequest = new ProcessRequest([
                'name' => $apiName,
            ]);

            $apiRequest->setSkipHumanReview(true);
            $apiRequest->setRawDocument($rawDocument);

            if (app()->environment() === 'local') {
                // get request info before sending
                Log::info('Request skip human review is ', ['text' => $apiRequest->getSkipHumanReview()]);
                Log::info('Request api name', ['text' => $apiRequest->getName()]);
                Log::info('Request document', ['text' => $apiRequest->getRawDocument()]);
                Log::info('Request has raw document', ['has' => $apiRequest->hasRawDocument()]);
            }

            // send document to Document AI
            // for easy disabling
            if (config('services.google.document_ai.enabled') == true) {
                $response = $client->processDocument($apiRequest);

                $document = $response->getDocument();
                $entities = $document->getEntities();

                if (app()->environment() === 'local') {

                    Log::info('Response ', ['response' => $response]);

                    Log::info('Document Info', [
                        'document' => $document,
                        'documentText' => $document->getText(),
                        'documentContent' => $document->getContent(),
                        'documentShard' => $document->getShardInfo(),
                        'hasError' => $document->hasError(),
                        'getDocumentLayout' => $document->getDocumentLayout(),
                        'getSource' => $document->getSource(),
                        'mimeType' => $document->getMimeType(),
                        'entities' => $entities,
                        'entitiesRel' => $document->getEntityRelations(),
                        'pages' => $document->getPages(),
                    ]);
                }

                $extractedData = [];

                foreach ($entities as $entity) {
                    $type = $entity->getType();
                    $mentionText = $entity->getMentionText();
                    $confidence = $entity->getConfidence();

                    $mentionTextTrimmed = ltrim($mentionText, '@');
                    $isEmail = filter_var($mentionTextTrimmed, FILTER_VALIDATE_EMAIL);

                    // Log::info('Entity', [
                    //     'type' => $type,
                    //     'mentionText' => $mentionText,
                    //     'isContact ' => strpos($type, 'contact'),
                    //     'isContactBool ' => strpos($type, 'contact') !== false,
                    //     'isEmail' => $isEmail,
                    //     'evaluate to ' => (strpos($type, 'contact') !== false && $isEmail),
                    // ]);

                    // This should skip assigning email to contact
                    if (strpos($type, 'contact') !== false && $isEmail) {
                        continue;
                    }

                    // Store to assoc array field_name => ocr_value
                    if (isset($extractedData[$type])) {
                        // If the existing value is a string, convert it to an array
                        if (is_string($extractedData[$type])) {
                            $extractedData[$type] = [$extractedData[$type]];
                        }
                        $extractedData[$type][] = $mentionText;
                    } else {
                        // Store the ocr field value as a string
                        $extractedData[$type] = $mentionText;
                    }

                    if (app()->environment() === 'local') {
                        Log::info('Entity', [
                            'type' => $type,
                            'mentionText' => $mentionText,
                            'confidence' => $confidence,
                        ]);
                    }
                }


                if ($returnType == 'response') {
                    return response()->json([
                        'parsedData' => $extractedData,
                    ]);
                } else if ($returnType == 'array') {
                    return $extractedData;
                }

                // Clean up the uploaded file
                // Storage::delete($path);
            }
        } catch (\Exception $e) {

            report($e->getMessage());
        }
    }
}
