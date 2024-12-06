<?php

namespace App\Http\Helpers;

class GovernmentMandateContributionsIdFormat
{
    public static function formatSsNumber(string $ssNumber)
    {
        return substr($ssNumber, 0, 2) . '-' . 
            substr($ssNumber, 2, 7) . '-' . 
            substr($ssNumber, 9, 1);
    }

    public static function formatPagibigMid(string $pagibigMid)
    {
        return substr($pagibigMid, 0, 4) . '-' . 
            substr($pagibigMid, 4, 4) . '-' .
            substr($pagibigMid, 8, 4);
    }

    public static function formatPhilhealthId(string $philhealthId)
    {
        return substr($philhealthId, 0, 2) . '-' . 
            substr($philhealthId, 2, 9) . '-' . 
            substr($philhealthId, 11, 1);
    }

    public static function formatTin(string $tin)
    {
        return substr($tin, 0, 3) . '-' . 
            substr($tin, 3, 3) . '-' . 
            substr($tin, 6, 3) . '-' . 
            substr($tin, 9, 3);
    }
}