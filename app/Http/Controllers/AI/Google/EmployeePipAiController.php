<?php

namespace App\Http\Controllers\AI\Google;

use App\Http\Controllers\Controller;
use App\Traits\EmployeePipAiAuth;
use Google\Cloud\AIPlatform\V1\Client\PredictionServiceClient;
use Google\Cloud\AIPlatform\V1\GenerateContentRequest;
use Google\Cloud\AIPlatform\V1\Content;
use Google\Cloud\AIPlatform\V1\Part;
use Illuminate\Http\Request;

class EmployeePipAiController extends Controller
{
    use EmployeePipAiAuth;

    const SYSTEM_INSTRUCTION = "You are an expert hr performance analyst that can give robust and thorough performance improvement plans based on employee's performance evaluation forms. You answer concisely, directly, and in bulleted format. You answer truthfully and straightforward based on the employee’s performance data and you plan what needs to be done for their personalized performance improvement plan. A rating of 1 means needs improvement, a 2 meets expectations, a 3 exceeds expectations, and a 4 means outstanding. (Do not put the numeric rating in the analysis, just write the corresponding equivalence.)";

    public function generatePerformancePlan(Request|array $request)
    {
        if (app()->environment('local')) {
            putenv('GOOGLE_SDK_PHP_LOGGING=true');
        }

        if (!(config('services.google.vertex_ai.enabled') == true && config('services.google.employee_pip_ai.enabled') == true)){
            return;
        }

        try {
            [$credentialsPath, $credentials] = $this->setCredentials();

            [$endpointId, $endpointLocation, $modelVer, $projectNumber] = $this->getEndpointAdddress();

            $clientOptions = [
                // customize 'apiEndpoint' as the default of the library is somewhat outdated.
                'apiEndpoint' => "$endpointLocation-aiplatform.googleapis.com",
                'credentials' => $credentialsPath,
            ];

            $client = new PredictionServiceClient($clientOptions);

            $contentPart = new Part([
                'text' => json_encode([                   "Employee Information" => [
                    "Employee Name" => is_array($request) ? $request['evaluatee_name'] : $request->input('evaluatee_name'),
                    "Department/Section" => is_array($request) ? $request['department_name'] : $request->input('department_name'),
                    "Position Title" => is_array($request) ? $request['evaluatee_position'] : $request->input('evaluatee_position'),
                    "Branch" => is_array($request) ? $request['branch_name'] : $request->input('branch_name'),
                    "Evaluator" => is_array($request) ? $request['evaluator_name'] : $request->input('evaluator_name'),
                    "Date Hired" => is_array($request) ? $request['evaluatee_hire_date'] : $request->input('evaluatee_hire_date'),
                ],
                "Instruction" => is_array($request) ? $request['instructions'] : $request->input('instructions'),

                "Performance Categories" => is_array($request) ? $request['performance_categories'] : $request->input('performance_categories')])
            ]);

            $content = new Content();
            $content->setRole('user');
            $content->setParts([$contentPart]);

            $systemInstruction = new Content();
            $systemInstruction->setRole('system');
            $systemInstruction->setParts([
                new Part([
                    'text' => self::SYSTEM_INSTRUCTION
                ])
            ]);

            $aiModel = "projects/$projectNumber/locations/$endpointLocation/endpoints/$endpointId";

            if($modelVer){
                $aiModel .= "@" . $modelVer;
            }

            $generateRequest = new GenerateContentRequest();
            $generateRequest->setModel($aiModel);
            $generateRequest->setContents([$content]);
            $generateRequest->setSystemInstruction($systemInstruction);

            $response = $client->generateContent($generateRequest);

            $responseContainer = $response->getCandidates();

            // why tf is this so hard to get the content fuck google
            $responseContent = $responseContainer->offsetGet(0)->getContent()->getParts()->offsetGet(0)->getText();

            $generationMetadata =   $response->getUsageMetadata();

            return $responseContent;
        } catch (\Throwable $th) {
            if (app()->environment('local')) {
                throw $th;
            } else {
                report($th);
            }
        }
    }
}
