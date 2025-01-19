<?php

namespace App\Livewire\HrManager\Separation\Coe;

use App\Http\Controllers\Separation\CoeController;
use App\Models\CoeRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class IssueCoeRequests extends Component
{

    public CoeRequest $coe;

    public function save(CoeController $controller)
    {
        // dump($this->coe->requestor->jobDetail);
        // dump($this->coe->requestor->lifecycle);
        // dd();
        $coePath = $controller->edit($this->coe);

        // sleep(3);

        $controller->update($this->coe, $coePath);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Certificate generated successfully',
        ]);
    }

    public function download(){

        $file = $this->coe->empCoeDoc->file_path;

        if (Storage::disk('public')->exists($file)) {
            return Storage::disk('public')->download($file);
        }

    }

    public function render()
    {
        return view('livewire.hr-manager.separation.coe.issue-coe-requests');
    }
}
