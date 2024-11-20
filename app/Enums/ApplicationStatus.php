<?php

namespace App\Enums;

enum ApplicationStatus: int
{
    case PENDING = 1;
    case ASSESSMENT_SCHEDULED = 2;
    case PRE_EMPLOYED = 3;
    case APPROVED = 4;
    case REJECTED = 5;

    /**
     * Return user-friendly application status labels.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending review',
            self::ASSESSMENT_SCHEDULED => 'Assessment scheduled',
            self::PRE_EMPLOYED => 'Pre employed',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }

    /**
     * Get an array of all guard types.
     *
     * @return array An array of guard types.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get an array of allowed status updates for pending applications.
     *
     * @return array An array of allowed status updates.
     */
    public static function allowedPendingStatusUpdates(): array
    {
        return [
            self::ASSESSMENT_SCHEDULED,
            self::REJECTED => 'Rejected',
        ];
    }
}
