<?php

namespace App\Enums;

enum IssueStatus: int
{
    case OPEN = 1;
    case RESOLVED = 2;
    case CLOSED = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::OPEN => __('Open'),
            self::RESOLVED => __('Resolved'),
            self::CLOSED => __('Closed'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::RESOLVED => 'success',
            self::OPEN => 'info',
            self::CLOSED => 'danger'
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::RESOLVED => 'circle-check-big',
            self::OPEN => 'square-pen',
            self::CLOSED => 'circle-slash'
        };
    }

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->getLabel();

            return $carry;
        }, []);
    }
}
