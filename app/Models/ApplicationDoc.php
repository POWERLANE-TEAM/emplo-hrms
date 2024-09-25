<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDoc extends Model
{
    use HasFactory;

    const CREATED_AT = 'submitted_at';

    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'application_doc_id';

    protected $guarded = [
        'application_doc_id',
        'submitted_at',
        'updated_at',
        'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns which pre-employment requirements the document belongs to
    public function preempRequirement(): BelongsTo
    {
        return $this->belongsTo(PreempRequirement::class, 'preemp_req_id', 'preemp_req_id');
    }

    // returns for which application the document is submitted to
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }

    // returns evaluator of the document
    public function evaluatedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluated_by', 'employee_id');
    }
}
