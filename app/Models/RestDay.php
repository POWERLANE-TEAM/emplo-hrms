<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestDay extends Model
{
    use HasFactory;

    protected $primaryKey = 'rest_day_id';

    protected $guarded = [
        'rest_day_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Accessor for day (e.g., Sunday).
     */
    protected function day(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $day = array_filter(Carbon::getDays(),
                    fn ($day) => $value === $day, ARRAY_FILTER_USE_KEY
                );

                return reset($day);
            }
        );
    }

    /**
     * Get the employee for the rest day.
     * 
     * @return BelongsTo<Employee, RestDay>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
