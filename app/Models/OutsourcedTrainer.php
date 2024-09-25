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

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    public function trainings(): MorphMany
    {
        return $this->morphMany(Training::class, 'trainer');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Training::class, 'comment');
    }

    public function trainingProviders(): BelongsTo
    {
        return $this->belongsTo(TrainingProvider::class, 'training_provider', 'training_provider_id');
    }
}
