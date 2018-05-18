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
            Carbon::createFromDate(2018, 5, 14), // Monday
            Carbon::createFromDate(2018, 5, 15)
        ));

        $this->assertEquals(3, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 14), // Monday
            Carbon::createFromDate(2018, 5, 17)
        ));

        $this->assertEquals(5, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 14), // Monday
            Carbon::createFromDate(2018, 5, 21)
        ));

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 13), // Sunday
            Carbon::createFromDate(2018, 5, 15)
        ));

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 12), // Saturday
            Carbon::createFromDate(2018, 5, 15)
        ));

        $date->setWeekendDays([Carbon::SUNDAY]);

        $this->assertEquals(2, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 12), // Saturday
            Carbon::createFromDate(2018, 5, 15)
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
            Carbon::createFromDate(2018, 5, 14), // Monday
            Carbon::createFromDate(2018, 5, 16)
        ));

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 13), // Sunday
            Carbon::createFromDate(2018, 5, 16)
        ));

        $this->assertEquals(2, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 13), // Sunday
            Carbon::createFromDate(2018, 5, 17)
        ));

        $date->addHoliday(Carbon::createFromDate(2018, 5, 16));

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 13), // Sunday
            Carbon::createFromDate(2018, 5, 17)
        ));

        $date->removeHoliday(Carbon::createFromDate(2018, 5, 16));

        $this->assertEquals(2, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 13), // Sunday
            Carbon::createFromDate(2018, 5, 17)
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
            Carbon::createFromDate(2018, 5, 11), // Friday
            Carbon::createFromDate(2018, 5, 20)
        ));

        $this->assertEquals(0, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 14), // Monday
            Carbon::createFromDate(2018, 5, 20)
        ));

        $this->assertEquals(1, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 14), // Monday
            Carbon::createFromDate(2018, 5, 22)
        ));

        $date->removeClosedDay(Carbon::createFromDate(2018, 5, 15));

        $this->assertEquals(2, $date->daysBetween(
            Carbon::createFromDate(2018, 5, 11), // Friday
            Carbon::createFromDate(2018, 5, 20)
        ));
    }
}