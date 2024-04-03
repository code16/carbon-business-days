<?php

namespace Code16\CarbonBusiness\Tests;

use Carbon\Carbon;
use Code16\CarbonBusiness\BusinessDays;
use PHPUnit\Framework\TestCase;

class CarbonBusinessDaysBetweenTest extends TestCase
{

    /** @test */
    function we_get_the_right_day_count_in_week_days()
    {
        $date = new BusinessDays();

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 14)->startOfDay(), // Monday
            Carbon::createFromDate(2018, 5, 15)->startOfDay()
        ));

        $this->assertEquals(3, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 14)->startOfDay(), // Monday
            Carbon::createFromDate(2018, 5, 17)->startOfDay()
        ));

        $this->assertEquals(5, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 14)->startOfDay(), // Monday
            Carbon::createFromDate(2018, 5, 21)->startOfDay()
        ));

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 13)->startOfDay(), // Sunday
            Carbon::createFromDate(2018, 5, 15)->startOfDay()
        ));

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 12)->startOfDay(), // Saturday
            Carbon::createFromDate(2018, 5, 15)->startOfDay()
        ));

        $date->setWeekendDays([Carbon::SUNDAY]);

        $this->assertEquals(2, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 12)->startOfDay(), // Saturday
            Carbon::createFromDate(2018, 5, 15)->startOfDay()
        ));
    }

    /** @test */
    function we_get_the_right_day_count_with_an_holiday()
    {
        $date = (new BusinessDays())
            ->addHoliday(Carbon::createFromDate(2018, 5, 15)); // Tuesday

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 14), // Monday
            Carbon::createFromDate(2018, 5, 15)
        ));

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 14)->startOfDay(), // Monday
            Carbon::createFromDate(2018, 5, 16)->startOfDay()
        ));

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 13)->startOfDay(), // Sunday
            Carbon::createFromDate(2018, 5, 16)->startOfDay()
        ));

        $this->assertEquals(2, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 13)->startOfDay(), // Sunday
            Carbon::createFromDate(2018, 5, 17)->startOfDay()
        ));

        $date->addHoliday(Carbon::createFromDate(2018, 5, 16));

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 13)->startOfDay(), // Sunday
            Carbon::createFromDate(2018, 5, 17)->startOfDay()
        ));

        $date->removeHoliday(Carbon::createFromDate(2018, 5, 16));

        $this->assertEquals(2, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 13)->startOfDay(), // Sunday
            Carbon::createFromDate(2018, 5, 17)->startOfDay()
        ));
    }

    /** @test */
    function we_get_the_right_day_count_with_a_closed_period()
    {
        $date = (new BusinessDays())
            ->addClosedPeriod(
                Carbon::createFromDate(2018, 5, 14),
                Carbon::createFromDate(2018, 5, 20)
            );

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 11)->startOfDay(), // Friday
            Carbon::createFromDate(2018, 5, 20)->startOfDay()
        ));

        $this->assertEquals(0, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 14), // Monday
            Carbon::createFromDate(2018, 5, 20)
        ));

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 14)->startOfDay(), // Monday
            Carbon::createFromDate(2018, 5, 22)->startOfDay()
        ));

        $date->removeClosedDay(Carbon::createFromDate(2018, 5, 15));

        $this->assertEquals(2, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 11)->startOfDay(), // Friday
            Carbon::createFromDate(2018, 5, 20)->startOfDay()
        ));
    }
}