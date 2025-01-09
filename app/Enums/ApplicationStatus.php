<?php

namespace App\Enums;

enum ApplicationStatus: int
{
    case PENDING = 1;
    case ASSESSMENT_SCHEDULED = 2;
    case FINAL_INTERVIEW_SCHEDULED = 3;
    case PRE_EMPLOYED = 4;
    case APPROVED = 5;
    case REJECTED = 6;

    /**
     * Return user-friendly application status labels.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending review',
            self::ASSESSMENT_SCHEDULED => 'Assessment scheduled',
            self::FINAL_INTERVIEW_SCHEDULED => 'Final interview scheduled',
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

    public static function qualifiedState(): array
    {
        return [
            self::ASSESSMENT_SCHEDULED,
            self::FINAL_INTERVIEW_SCHEDULED,
        ];
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
            self::REJECTED,
        ];
    }

    /**
     * Get an array of allowed status updates for pending applications.
     *
     * @return array An array of allowed status updates.
     */
    public static function allowedAssessedStatusUpdates(): array
    {
        return [
            self::FINAL_INTERVIEW_SCHEDULED,
            self::REJECTED,
        ];
    }

    /**
     * Get an array of allowed status updates for pending applications.
     *
     * @return array An array of allowed status updates.
     */
    public static function allowedFinalInterviewStatusUpdates(): array
    {
        return [
            self::PRE_EMPLOYED,
            self::REJECTED,
        ];
    }

    /**
     * Match substrings to enum case values.
     *
     * @param string $status
     * @return int|null
     */
    public static function fromNameSubstring(string $status): ?int
    {
        $status = strtolower(trim($status));
        foreach (self::cases() as $case) {
            $caseName = strtolower(str_replace(['_'], '', $case->name));
            if (str_contains($caseName, $status)) {
                return $case->value;
            }
        }
        return null;
    }
}
