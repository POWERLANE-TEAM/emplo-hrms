<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantDoc extends Model
{
    use HasFactory;

    protected $primaryKey = 'applicant_doc_id';

    protected $guarded = [
        'applicant_doc_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'checked_by', 'applicant_doc_id');
    }
}