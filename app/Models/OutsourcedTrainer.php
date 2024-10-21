<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OutsourcedTrainer extends Model
{
    use HasFactory;

    protected $primaryKey = 'training_provider_id';

    protected $guarded = [
        'trainer_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the trainings associated with the outsourced trainer.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function trainings(): MorphMany
    {
        return $this->morphMany(Training::class, 'trainer');
    }

    /**
     * Get the comments associated with the outsourced trainer.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Training::class, 'comment');
    }

    /**
     * Get the training provider the outsourced trainer belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(TrainingProvider::class, 'training_provider', 'training_provider_id');
    }
}
