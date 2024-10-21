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

    /**
     * Get the pre-employment requirements the application document belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function preempRequirement(): BelongsTo
    {
        return $this->belongsTo(PreempRequirement::class, 'preemp_req_id', 'preemp_req_id');
    }

    /**
     * Get the job application that owns the application documents.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }

    /**
     * Get the employee who is the evaluator of the application document.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evaluatedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluated_by', 'employee_id');
    }
}
