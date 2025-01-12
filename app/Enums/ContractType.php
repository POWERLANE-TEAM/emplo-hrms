<?php

namespace App\Enums;

enum ContractType: string
{
    case CONTRACT = 'contract';
    case ADDENDUM = 'addendum';

    public function getLabel(): string
    {
        return match ($this) {
            self::CONTRACT => 'Contract',
            self::ADDENDUM => 'Addendum',
        };
    }

    /**
     * Return array of each cases and scalar values.
     */
    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->getLabel();

            return $carry;
        }, []);
    }
}
