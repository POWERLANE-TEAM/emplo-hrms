<?php

namespace App\Livewire\Employee\Performances\Regular\ImprovementPlan;

use App\Http\Controllers\RegularPerformancePlanController;
use App\Models\RegularPerformance;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Create extends Component
{
    #[Locked]
    public $pipData;

    public RegularPerformance $performance;

    public $pipId;

    public $pipDetails;

    public ?string $method;

    public ?string $routeMethod;

    public function boot()
    {
        $controller = new RegularPerformancePlanController();

        if($this->performance->pip()->exists()){
            $this->method = 'PATCH';
            $this->routeMethod = 'update';

        }



    }

    public function save(){
        // if($this->method == 'PATCH'){
        //     return $controller->update([
        //         'pipId' => $this->performance->pip->pip_id,
        //         'pipDetails' => $this->pipDetails,
        //     ]);
        // }

        // return $controller->store(
        //     [
        //         'performanceId' => $this->performance->regular_performance_id,
        //         'pipDetails' => $this->pipDetails,
        //     ]
        // );
    }

    public function render()
    {
        return view('livewire.employee.performances.regular.improvement-plan.create');
    }
}
