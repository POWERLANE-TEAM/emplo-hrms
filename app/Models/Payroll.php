<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'payroll_id';

    protected $guarded = [
        'payroll_id',
    ];

    protected function payout(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)->format('F d, Y'),
        );
    }

    public function getCutOffAttribute(): string
    {
        $start = Carbon::make($this->cut_off_start)->format('F d');
        $end = Carbon::make($this->cut_off_end)->format('F d, Y');

        return "{$start} - {$end}";
    }

    /**
     * Get the overtime summaries of the payroll.
     */
    public function overtimeApprovals(): HasMany
    {
        return $this->hasMany(OvertimePayrollApproval::class, 'payroll_id', 'payroll_id');
    }
}