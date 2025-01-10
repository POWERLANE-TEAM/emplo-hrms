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

    const SYSTEM_INSTRUCTION = "You are an expert hr performance analyst that can give robust and thorough performance improvement plans based on employee's performance evaluation forms. You answer concisely, directly, and in bulleted format. You answer truthfully and straightforward based on the employee’s performance data and you plan what needs to be done for their personalized performance improvement plan.";

    public function generatePerformancePlan(Request|array $request)
    {
        if (app()->environment('local')) {
            putenv('GOOGLE_SDK_PHP_LOGGING=true');
        }

        try {
            [$credentialsPath, $credentials] = $this->setCredentials();



            [$endpointId, $endpointLocation, $modelVer, $projectNumber] = $this->getEndpointAdddress();

            $clientOptions = [
                'apiEndpoint' => "$endpointLocation-aiplatform.googleapis.com",
                'credentials' => $credentialsPath,
            ];

            $client = new PredictionServiceClient($clientOptions);

            $employeeInfo = new Part([
                'text' => json_encode([
                    "Employee Information" => [
                        "Employee Name" => is_array($request) ? $request['evaluatee_name'] : $request->input('evaluatee_name'),
                        "Department/Section" => is_array($request) ? $request['department_name'] : $request->input('department_name'),
                        "Position Title" => is_array($request) ? $request['evaluatee_position'] : $request->input('evaluatee_position'),
                        "Branch" => is_array($request) ? $request['branch_name'] : $request->input('branch_name'),
                        "Evaluator" => is_array($request) ? $request['evaluator_name'] : $request->input('evaluator_name'),
                        "Date Hired" => is_array($request) ? $request['evaluatee_hire_date'] : $request->input('evaluatee_hire_date'),
                    ],
                ])
            ]);

            $instruction = new Part([
                'text' => json_encode([
                    "Instruction" => is_array($request) ? $request['instructions'] : $request->input('instructions')
                ])
            ]);

            $performanceCategories = new Part([
                'text' => json_encode([
                    "Performance Categories" => is_array($request) ? $request['performance_categories'] : $request->input('performance_categories')
                ])
            ]);

            $content = new Content();
            $content->setRole('user');
            $content->setParts([$employeeInfo, $instruction, $performanceCategories]);

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


            // dump($content);
            // dd($generateRequest);


            $response = $client->generateContent($generateRequest);

            dd($response);
        } catch (\Throwable $th) {
            if (app()->environment('local')) {
                throw $th;
            } else {
                report($th);
            }
        }
    }
}
