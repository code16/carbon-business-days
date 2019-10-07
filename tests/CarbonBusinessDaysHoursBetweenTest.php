<?php


namespace Code16\CarbonBusiness\Tests;


use Carbon\Carbon;
use Code16\CarbonBusiness\BusinessDays;
use PHPUnit\Framework\TestCase;

class CarbonBusinessDaysHoursBetweenTest extends TestCase
{
    /**
     * @test
     */
    function right_count_between_in_hours()
    {
        $businessDays = new BusinessDays();

        $this->assertEquals(1, $businessDays->hoursBetween(
            Carbon::createFromDate(2019, 10, 7)->startOfDay(),
            Carbon::createFromDate(2019, 10, 7)->startOfDay()->addHour()
        ));

        $this->assertEquals(24, $businessDays->hoursBetween(
            Carbon::createFromDate(2019, 10, 7)->startOfDay(), // Monday
            Carbon::createFromDate(2019, 10, 8)->startOfDay()
        ));

        $this->assertEquals(48, $businessDays->hoursBetween(
            Carbon::createFromDate(2019, 10, 6)->startOfDay(), // Sunday
            Carbon::createFromDate(2019, 10, 9)->startOfDay() // Wednesday
        ));

        $this->assertEquals(6, $businessDays->hoursBetween(
            Carbon::createFromDate(2019, 10, 5)->startOfDay(), // Saturday
            Carbon::createFromDate(2019, 10, 7)->startOfDay()->addHours(6) // Monday
        ));
    }

    /**
     * @test
     */
    function right_count_between_in_hours_with_holiday()
    {
        $businessDays = (new BusinessDays())
            ->addHoliday(Carbon::createFromDate(2019, 10, 7)); // Monday

        $this->assertEquals(0, $businessDays->hoursBetween(
            Carbon::createFromDate(2019, 10, 7)->startOfDay(),
            Carbon::createFromDate(2019, 10, 7)->startOfDay()->addHour()
        ));

        $this->assertEquals(15, $businessDays->hoursBetween(
            Carbon::createFromDate(2019, 10, 5), // Saturday
            Carbon::createFromDate(2019, 10, 8)->midDay()->addHours(3) // Tuesday
        ));

        $businessDays
            ->addHoliday(Carbon::createFromDate(2019, 10, 9)); // Wednesday

        $this->assertEquals(74, $businessDays->hoursBetween(
            Carbon::createFromDate(2019, 10, 4)->midDay()->addHour(), // Friday at 1
            Carbon::createFromDate(2019, 10, 11)->midDay()->addHours(3) // Friday at 3
        ));
    }

    /**
     * @test
     */
    function right_count_between_in_hours_with_closed_period()
    {
        $businessDays = (new BusinessDays())
            ->addClosedPeriod(
                Carbon::createFromDate(2019, 12, 23)->startOfDay(), // Monday
                Carbon::createFromDate(2019, 12, 26)->endOfDay() // Thursday
            );

        $this->assertEquals(0, $businessDays->hoursBetween(
            Carbon::createFromDate(2019, 12, 24)->startOfDay(),
            Carbon::createFromDate(2019, 12, 25)->startOfDay()->addHour()
        ));

        $this->assertEquals(48, $businessDays->hoursBetween(
            Carbon::createFromDate(2019, 12, 20)->startOfDay(), // Friday
            Carbon::createFromDate(2019, 12, 27)->endOfDay() // Friday
        ));

        $businessDays->addClosedPeriod(
            Carbon::createFromDate(2019, 12, 31)->startOfDay()->addHours(14), // Tuesday at 2 PM
            Carbon::createFromDate(2020, 1, 1)->endOfDay() // Wednesday
        );

        $this->assertEquals(28, $businessDays->hoursBetween(
            Carbon::createFromDate(2019, 12, 30)->midDay(), // Monday at noon
            Carbon::createFromDate(2020, 1, 2)->midDay()->addHours(4) // Thursday at 4
        ));
    }
}