<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OutsourcedTrainer extends Model
{
    use HasFactory;

    protected $primaryKey = 'trainer_id';

    protected $guarded = [
        'trainer_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the outsources trainer's full name.
     *
     * @return string
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['last_name'].', '.
                $attributes['first_name'].' '.
                $attributes['middle_name'],
        );
    }

    /**
     * Get the trainings associated with the outsourced trainer.
     */
    public function trainings(): MorphMany
    {
        return $this->morphMany(Training::class, 'trainer');
    }

    /**
     * Get the comments associated with the outsourced trainer.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Training::class, 'comment');
    }

    /**
     * Get the training provider the outsourced trainer belongs to.
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(TrainingProvider::class, 'training_provider', 'training_provider_id');
    }
}
