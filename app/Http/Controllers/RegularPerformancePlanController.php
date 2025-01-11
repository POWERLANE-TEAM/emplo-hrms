<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AI\Google\EmployeePipAiController;
use App\Models\RegularPerformance;
use App\Http\Helpers\RouteHelper;
use App\Models\PerformanceCategory;
use App\Models\PipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
    public function create(int $performance)
    {
        $performanceEvalForm = RouteHelper::validateModel(RegularPerformance::class, $performance);

        $data = Session::get('performance_plan_data_' . $performance);

        return view('employee.performance.improvement-plan.regular.create', ['performance' => $performanceEvalForm, 'pipData' => $data, 'unsaved' => true]);
    }

    public function generate(Request $request)
    {
        $performanceEvaluation = RouteHelper::validateModel(RegularPerformance::class, $request->input('performance-form') ?? abort(400));

        $employee = [
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



        $generator = new EmployeePipAiController();

        $instruction = "Please conduct evaluations at annual evaluation. For each evaluation, indicate the employee's performance by writing a number between 1 and 4 on the blank line to the right of each performance category, in the appropriate column. Use the following scale: 1 = Needs Improvement, 2 = Meets Expectations, 3 = Exceeds Expectations, 4 = Outstanding.";

        $generateReq = array_merge($employee, ["performance_categories" => $performanceCategories], ['instructions' => $instruction]);

        $response = $generator->generatePerformancePlan($generateReq);

        Session::put('performance_plan_data_' . $performanceEvaluation->regular_performance_id, $response);

        return redirect()->route('employee.performances.plan.improvement.regular.create', ['performance' => $performanceEvaluation]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, bool $isValidated = false)
    {
        if ($request instanceof Request) {
            // dump($request->all());
        }

        $performanceForm = RouteHelper::validateModel(RegularPerformance::class, $request['performanceId'] ?? abort(404));

        if($performanceForm->pip()->exists()){
            abort(400, 'Improvement plan already exists for this performance evaluation.');
        }

        $validated = $request->validate([
            'performanceId' => 'required|exists:regular_performances,regular_performance_id',
            'pipDetails' => 'required',
        ]);

        // dd($validated);

        $pipPlan = PipPlan::create([
            'regular_performance_id' => $validated['performanceId'],
            'details' => $validated['pipDetails'],
        ]);

        if(!$isValidated){
            return redirect()->route('employee.performances.plan.improvement.regular.generated', ['pip' => $pipPlan]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(int $pip)
    {
        $pipPlan = RouteHelper::validateModel(PipPlan::class, $pip);
        return view('employee.performance.improvement-plan.regular.generated', ['pip' => $pipPlan]);
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
    public function update(Request $request, bool $isValidated = false)
    {
        if ($request instanceof Request) {
            // dump($request->all());
        }

        $validated = $request->validate([
            'pipId' => 'required',
            'pipDetails' => 'required',
        ]);

        $pipPlan = RouteHelper::validateModel(PipPlan::class, $request['pipId']);

        $pipPlan->update([
            'details' => $validated['pipDetails'],
        ]);

        if(!$isValidated){
            return redirect()->route('employee.performances.plan.improvement.regular.generated', ['pip' => $pipPlan]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
