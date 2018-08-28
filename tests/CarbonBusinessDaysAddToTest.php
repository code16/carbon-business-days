<?php

namespace Code16\CarbonBusiness\Tests;

use Carbon\Carbon;
use Code16\CarbonBusiness\BusinessDays;
use PHPUnit\Framework\TestCase;

class CarbonBusinessDaysAddToTest extends TestCase
{

    /** @test */
    function we_can_add_business_days_to_a_date()
    {
        $date = new BusinessDays();

        $this->assertEquals("2018-05-15", $date->addDaysTo(
            Carbon::createFromDate(2018, 5, 14), // Monday
            1
        )->format("Y-m-d"));

        $this->assertEquals("2018-05-18", $date->addDaysTo(
            Carbon::createFromDate(2018, 5, 14), // Monday
            4
        )->format("Y-m-d"));

        $this->assertEquals("2018-05-28", $date->addDaysTo(
            Carbon::createFromDate(2018, 5, 14), // Monday
            10
        )->format("Y-m-d"));
    }

    /** @test */
    function we_take_account_for_holidays()
    {
        $date = new BusinessDays();

        $date->addHoliday(Carbon::createFromDate(2018, 5, 15));

        $this->assertEquals("2018-05-16", $date->addDaysTo(
            Carbon::createFromDate(2018, 5, 14), // Monday
            1
        )->format("Y-m-d"));

        $this->assertEquals("2018-05-21", $date->addDaysTo(
            Carbon::createFromDate(2018, 5, 14), // Monday
            4
        )->format("Y-m-d"));

        $this->assertEquals("2018-05-29", $date->addDaysTo(
            Carbon::createFromDate(2018, 5, 14), // Monday
            10
        )->format("Y-m-d"));
    }

    /** @test */
    function we_take_into_account_closed_periods()
    {
        $date = new BusinessDays();

        $date->addClosedPeriod(
            Carbon::createFromDate(2018, 5, 15),
            Carbon::createFromDate(2018, 5, 17)
        );

        $this->assertEquals("2018-05-18", $date->addDaysTo(
            Carbon::createFromDate(2018, 5, 14), // Monday
            1
        )->format("Y-m-d"));

        $this->assertEquals("2018-05-23", $date->addDaysTo(
            Carbon::createFromDate(2018, 5, 14), // Monday
            4
        )->format("Y-m-d"));

        $this->assertEquals("2018-05-31", $date->addDaysTo(
            Carbon::createFromDate(2018, 5, 14), // Monday
            10
        )->format("Y-m-d"));
    }

    /** @test */
    function we_take_into_account_custom_weekendDays()
    {
        $date = new BusinessDays();

        $date->setWeekendDays([Carbon::SUNDAY]);

        $this->assertEquals("2018-05-25", $date->addDaysTo(
            Carbon::createFromDate(2018, 5, 14), // Monday
            10
        )->format("Y-m-d"));
    }

    /** @test */
    function we_can_subtract_business_days_from_a_date()
    {
        $date = new BusinessDays();

        $this->assertEquals("2018-05-11", $date->subDaysFrom(
            Carbon::createFromDate(2018, 5, 14), // Monday
            1
        )->format("Y-m-d"));

        $this->assertEquals("2018-05-08", $date->subDaysFrom(
            Carbon::createFromDate(2018, 5, 14), // Monday
            4
        )->format("Y-m-d"));

        $this->assertEquals("2018-04-30", $date->subDaysFrom(
            Carbon::createFromDate(2018, 5, 14), // Monday
            10
        )->format("Y-m-d"));
    }

    /** @test */
    function we_can_get_closed_days_as_an_array()
    {
        $date = new BusinessDays();

        $date->addClosedPeriod(
            Carbon::createFromDate(2018, 5, 15),
            Carbon::createFromDate(2018, 5, 16)
        );

        $this->assertEquals(Carbon::createFromDate(2018, 5, 15)->startOfDay(), $date->getClosedDays()[0]);
        $this->assertEquals(Carbon::createFromDate(2018, 5, 16)->startOfDay(), $date->getClosedDays()[1]);
    }

    /** @test */
    function we_can_get_holidays_as_an_array()
    {
        $date = new BusinessDays();

        $date->addHoliday(Carbon::createFromDate(2018, 5, 15));
        $date->addHoliday(Carbon::createFromDate(2018, 5, 16));

        $this->assertEquals(Carbon::createFromDate(2018, 5, 15)->startOfDay(), $date->getHolidays()[0]);
        $this->assertEquals(Carbon::createFromDate(2018, 5, 16)->startOfDay(), $date->getHolidays()[1]);
    }
}