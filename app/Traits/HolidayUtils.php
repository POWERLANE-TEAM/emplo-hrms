<?php

namespace App\Traits;

use App\Models\Holiday;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

trait HolidayUtils
{
    public $regularHolidays;

    public $specialHolidays;

    /**
     * Get collection of `Holiday` models of regular type.
     * 
     * @param Carbon|int $year
     * @return Collection<int, mixed>
     */
    public function getRegularHolidays(Carbon|int $year)
    {
        if ($year instanceof Carbon) {
            $year = $year->year;
        }

        return Holiday::regular()
            ->get()
            ->each(function ($regHoliday) use ($year) {
                return $regHoliday->date = Carbon::createFromFormat('m-d', $regHoliday->date)
                    ->setYear($year)
                    ->toDateString();
            });
    }

    /**
     * Get collection of `Holiday` models of special(working/non-working) type.
     * 
     * @param Carbon|int $year
     * @return Collection<int, mixed>
     */
    public function getSpecialHolidays(Carbon|int $year)
    {
        if ($year instanceOf Carbon) {
            $year = $year->year;
        }

        return Holiday::special()
            ->get()
            ->each(function ($speHoliday) use ($year) {
                return $speHoliday->date = Carbon::createFromFormat('m-d', $speHoliday->date)
                    ->setYear($year)
                    ->toDateString();
            });
    }

    /**
     * Check if date is a holiday, either regular or special.
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public function isHoliday(Carbon|string $date): bool
    {
        return $this->isRegularHoliday($date) || $this->isSpecialHoliday($date);
    }

    /**
     * Check if date is a regular holiday.
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public function isRegularHoliday(Carbon|string $date): bool
    {
        $date = $this->formatDate($date);

        return ! is_null($this->regularHolidays->firstWhere('date', $date));
    }

    /**
     * Check if date is a special(working/non-working) holiday.
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public function isSpecialHoliday(Carbon|string $date): bool
    {
        $date = $this->formatDate($date);

        return ! is_null($this->specialHolidays->firstWhere('date', $date));
    }

    /**
     * Check if date is not a regular holiday.
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public function isNotRegularHoliday(Carbon|string $date): bool
    {
        return ! $this->isRegularHoliday($date);
    }

    /**
     * Check if date is not a special holiday.
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public function isNotSpecialHoliday(Carbon|string $date): bool
    {
        return ! $this->isSpecialHoliday($date);
    }

    /**
     * Check if date is not a holiday.
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public function isNotHoliday(Carbon|string $date): bool
    {
        return ! $this->isHoliday($date);
    }

    /**
     * If instance of carbon, make into ->toDateString(), else return as-is.
     * 
     * @param Carbon|string $date
     * @return string
     */
    private function formatDate(Carbon|string $date): string
    {
        return $date instanceOf Carbon ? $date->toDateString() : $date;
    }
}