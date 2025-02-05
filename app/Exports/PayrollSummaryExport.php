<?php

namespace App\Exports;

use App\Models\Payroll;
use App\Models\PayrollSummary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayrollSummaryExport implements FromCollection, WithHeadings
{
    public $payroll;

    public function __construct($payroll) {
        if (! $payroll) {
            $this->payroll = Payroll::latest('cut_off_start')->first()->payroll_id;
        }
        
        $this->payroll = $payroll;
    }

    public function headings(): array
    {
        return [
            'Empno',
            'Name',
            'Bdate',
            'Edate',
            'Reg',
            'RegND',
            'RegOT',
            'RegOTND',
            'Rest',
            'RestND',
            'RestOT',
            'RestOTND',
            'RegHol',
            'RegHolND',
            'RegHolOT',
            'RegHOTND',
            'RegHR',
            'RegHRND',
            'RegHROT',
            'RegHROTND',
            'SpeHol',
            'SpeHolND',
            'SpeHolOT',
            'SpeHOTND',
            'SpeHolR',
            'SpeHRND',
            'SpeHROT',
            'SpeHROTND',
            'Absence',
            'Utime',
            'Tardy',
        ];
    }

    public function collection()
    {
        return PayrollSummary::where('payroll_id', $this->payroll)
            ->with([
                'employee' => fn ($query) => $query->select([
                    'employee_id',
                    'first_name',
                    'middle_name',
                    'last_name',
                ]),
                'payroll',
            ])
            ->whereHas('employee', fn ($query) => $query->activeEmploymentStatus())
            ->get()
            ->map(function ($psum) {
                return [
                    $psum->employee->employee_id,
                    $psum->employee->full_name,
                    $psum->payroll->cut_off_start,
                    $psum->payroll->cut_off_end,
                    $psum->reg_hrs,
                    $psum->reg_nd,
                    $psum->reg_ot,
                    $psum->reg_ot_nd,
                    $psum->rest_hrs,
                    $psum->rest_nd,
                    $psum->rest_ot,
                    $psum->rest_ot_nd,
                    $psum->reg_hol_hrs,
                    $psum->reg_hol_nd,
                    $psum->reg_hol_ot,
                    $psum->reg_hol_ot_nd,
                    $psum->reg_hol_rest_hrs,
                    $psum->reg_hol_rest_nd,
                    $psum->reg_hol_rest_ot,
                    $psum->reg_hol_rest_ot_nd,
                    $psum->spe_hol_hrs,
                    $psum->spe_hol_nd,
                    $psum->spe_hol_ot,
                    $psum->spe_hol_ot_nd,
                    $psum->spe_hol_rest_hrs,
                    $psum->spe_hol_rest_nd,
                    $psum->spe_hol_rest_ot,
                    $psum->spe_hol_rest_ot_nd,
                    $psum->abs_days,
                    $psum->ut_hours,
                    $psum->td_hours,
                ];
            });
    }
}
