<?php

namespace App\Enums;

enum IssueConfidentiality: int
{
    case SHARE_WITH_RELEVANT_PARTIES = 1;
    case INTERNAL_USE_ONLY = 2;
    case PUBLIC_SUMMARY = 3;
    case DISCREET_HANDLING = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::SHARE_WITH_RELEVANT_PARTIES => __('Share with Relevant Parties'),
            self::INTERNAL_USE_ONLY => __('Internal Use Only'),
            self::PUBLIC_SUMMARY => __('Public Summary'),
            self::DISCREET_HANDLING => __('Discreet Handling'),
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::SHARE_WITH_RELEVANT_PARTIES => __('The complaint will be shared with specific individuals or departments involved in addressing the issue but not broadly distributed.'),
            self::INTERNAL_USE_ONLY => __('The complaint will be used internally for review and resolution but not disclosed beyond the HR department or specific personnel.'),
            self::PUBLIC_SUMMARY => __('A summary of the complaint may be shared publicly (e.g., aggregated data) but without revealing specific details or identities.'),
            self::DISCREET_HANDLING => __('The complaint will be handled discreetly by HR with minimal sharing, focusing on resolving the issue while maintaining confidentiality as much as possible.'),
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
