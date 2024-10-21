<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingProvider extends Model
{
    use HasFactory;

    protected $primaryKey = 'training_provider_id';

    protected $fillable = [
        'training_provider_name',
    ];

    /**
     * Get the outsourced trainers associated with the training provider.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outsourcedTrainers(): HasMany
    {
        return $this->hasMany(OutsourcedTrainer::class, 'training_provider', 'training_provider_id');
    }
}
