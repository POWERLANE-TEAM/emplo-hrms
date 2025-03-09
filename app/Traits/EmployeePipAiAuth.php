<?php

namespace App\Traits;

use Google\Cloud\AIPlatform\V1\Client\ModelServiceClient;
use Google\Cloud\AIPlatform\V1\ListModelsRequest;
use Illuminate\Support\Facades\File;

trait EmployeePipAiAuth
{
    /**
     * Retrieves the credentials from the specified path or the default configuration path.
     *
     * @param  string|null  $path  The path to the credentials file. If null, the default path from configuration is used.
     * @return array An array containing the credentials path and the decoded credentials.
     */
    public function getCredentials(?string $path = null)
    {
        $path = $path ?? config('services.google.employee_pip_ai.credential_path');
        $credentialsPath = storage_path($path);

        return [$credentialsPath, json_decode(file_get_contents($credentialsPath), true)];
    }

    /**
     * Sets the environment variables for Google Application Credentials and Project ID.
     *
     * @param  string|null  $path  The path to the credentials file. If null, the default path from configuration is used.
     * @return void
     */
    public function setCredentials(?string $path = null)
    {
        [$credentialsPath, $credentials] = $this->getCredentials($path);

        putenv('GOOGLE_APPLICATION_CREDENTIALS='.$credentialsPath);
        $projectId = $credentials['project_id'];
        putenv("GOOGLE_CLOUD_PROJECT=$projectId");

        return [$credentialsPath, $credentials];
    }

    /**
     * Retrieves the processor ID and version from the configuration.
     *
     * @return array An array containing the processor ID and version.
     */
    public function getModel()
    {
        $endpointId = config('services.google.employee_pip_ai.endpoint_id');
        $endpointLocation = config('services.google.employee_pip_ai.endpoint_loc');
        $modelVer = config('services.google.employee_pip_ai.model_ver');

        return [$endpointId, $endpointLocation, $modelVer];
    }

    public function getEndpointAdddress()
    {
        $projectNumber = config('services.google.employee_pip_ai.project_number');

        return array_merge($this->getModel(), [$projectNumber]);
    }

    public function isEndpointAccessible()
    {
        if (app()->environment('local')) {
            putenv('GOOGLE_SDK_PHP_LOGGING=true');
        }

        [$credentialsPath, $credentials] = $this->getCredentials();

        if (! File::exists($credentialsPath)) {
            report(new \Exception('Credentials file not found at '.File::path($credentialsPath)));

            return false;
        }

        [$endpointId, $endpointLocation, $modelVer, $projectNumber] = $this->getEndpointAdddress();

        $clientOptions = [
            'apiEndpoint' => "$endpointLocation-aiplatform.googleapis.com:443",
            'credentials' => $credentialsPath,
        ];

        try {
            $client = new ModelServiceClient($clientOptions);
            $request = new ListModelsRequest;
            $request->setParent("projects/$projectNumber/locations/$endpointLocation");
            $client->listModels($request);

            return true;
        } catch (\Exception $e) {
            report($e);

            return false;
        }
    }
}
