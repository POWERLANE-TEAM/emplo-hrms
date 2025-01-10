<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AI\Google\EmployeePipAiController;
use App\Models\RegularPerformance;
use App\Http\Helpers\RouteHelper;
use App\Models\PerformanceCategory;
use Illuminate\Http\Request;

class RegularPerformancePlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, ?int $performance = null)
    {
        $performanceEvaluation = RouteHelper::validateModel(RegularPerformance::class, $request->input('performance-form') ?? $performance);

        $employee =[
            'evaluatee_name' => $performanceEvaluation->employeeEvaluatee->full_name,
            'evaluatee_hire_date' => $performanceEvaluation->employeeEvaluatee->application->hired_at ? \Carbon\Carbon::parse($performanceEvaluation->employeeEvaluatee->application->hired_at)->format('F j, Y') : 'No record',
            'evaluatee_position' => $performanceEvaluation->employeeEvaluatee->jobTitle->job_title,
            'department_name' => $performanceEvaluation->employeeEvaluatee->jobTitle->department->department_name,
            'branch_name' => $performanceEvaluation->employeeEvaluatee->specificArea->area_name,
            'evaluator_name' => auth()->user()->account->full_name,
        ];


        $performanceEvaluation->loadMissing('categoryRatings');

        $categoryRatings = $performanceEvaluation->categoryRatings;

        $performanceCategories;

        foreach ($categoryRatings as $categoryRating) {
            $performanceCategories[] = [
                'category' => $categoryRating->category->perf_category_id . '. ' . $categoryRating->category->perf_category_name,
                'description' => $categoryRating->category->perf_category_desc,
                'rating' => [
                    "annual" => $categoryRating->perf_rating_id,
                ],
            ];
        }


        if(!$performance){
            $generator = new EmployeePipAiController();

            $instruction = "";

            $generateReq = array_merge($employee, ["performance_categories" => $performanceCategories], ['instructions' => $instruction]);

            $response = $generator->generatePerformancePlan($generateReq);

            return redirect()->route('employee.performances.regulars.plan.improvement.show', ['data' => $response]);
        }

        dump($performanceEvaluation);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request|array $request)
    {

        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($data)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
