<?php

namespace Code16\CarbonBusiness;

use Carbon\Carbon;

class FrenchHolidays
{

    /**
     * @param int $year
     * @return array
     */
    public static function getForYear(int $year): array
    {
        $holidays = [
            Carbon::createFromDate($year, 1, 1),
            Carbon::createFromDate($year, 5, 1),
            Carbon::createFromDate($year, 5, 8),
            Carbon::createFromDate($year, 7, 14),
            Carbon::createFromDate($year, 8, 15),
            Carbon::createFromDate($year, 11, 1),
            Carbon::createFromDate($year, 11, 11),
            Carbon::createFromDate($year, 12, 25),
        ];

        $easter = Carbon::createFromTimestamp(easter_date($year));
        $holidays[] = $easter->copy()->addDay(); // Easter Monday
        $holidays[] = $easter->copy()->addDays(39); // Ascension Tuesday
        $holidays[] = $easter->copy()->addDays(50); // Pentecost Monday

        return $holidays;
    }

    /**
     * @param int $year
     * @return array
     */
    public static function getForAlsaceMoselleForYear(int $year): array
    {
        return array_merge(
            static::getForYear($year), [
                Carbon::createFromTimestamp(easter_date($year))->subDays(2), // Easter Friday
                Carbon::createFromDate($year, 12, 26) // Saint Stephen
            ]
        );
    }
}