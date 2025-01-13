<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Training extends Model
{
    use HasFactory;

    protected $primaryKey = 'training_id';

    protected $guarded = [
        'training_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the parent model (Employee or OutsourcedTrainer) that the training record belongs to.
     */
    public function trainer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the employee trainee that owns the training record.
     */
    public function employeeTrainee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'trainee', 'employee_id');
    }

    /**
     * Get the parent model (Employee or OutsourcedTrainer) that the training record belongs to.
     */
    public function comment(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the employee who is the HR personnel that prepared the training record.
     */
    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'prepared_by', 'employee_id');
    }

    /**
     * Get the employee — most likely the HR Manager — who reviewed and approved the training record.
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reviewed_by', 'employee_id');
    }
}
