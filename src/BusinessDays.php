<?php

namespace Code16\CarbonBusiness;

use Carbon\Carbon;

class BusinessDays
{
    /** @var array */
    protected $weekendDays = [
        Carbon::SATURDAY+1, Carbon::SUNDAY+1
    ];

    /** @var array */
    protected $holidays = [];

    /** @var array */
    protected $closedDays = [];

    /**
     * @param array $weekendDays
     * @return BusinessDays
     */
    public function setWeekendDays(array $weekendDays): self
    {
        $this->weekendDays = array_map(function($day) {
            return $day + 1;
        }, $weekendDays);

        return $this;
    }

    /**
     * @param Carbon $date
     * @return BusinessDays
     */
    public function addHoliday(Carbon $date): self
    {
        if(!$this->isHoliday($date)) {
            $this->holidays[] = $date->format("Ymd");
        }

        return $this;
    }

    /**
     * @param array $dates
     * @return BusinessDays
     */
    public function addHolidays(array $dates): self
    {
        foreach($dates as $date) {
            $this->addHoliday($date);
        }

        return $this;
    }

    /**
     * @param Carbon $date
     * @return BusinessDays
     */
    public function removeHoliday(Carbon $date): self
    {
        if($k = array_search($date->format("Ymd"),$this->holidays) !== false) {
            array_splice($this->holidays, $k, 1);
        }

        return $this;
    }

    /**
     * @param Carbon $day
     * @return bool
     */
    public function isWeekendDay(Carbon $day): bool
    {
        return array_search($day->dayOfWeek + 1, $this->weekendDays) !== false;
    }

    /**
     * @param Carbon $date
     * @return bool
     */
    public function isHoliday(Carbon $date): bool
    {
        return array_search($date->format("Ymd"), $this->holidays) !== false;
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     * @return BusinessDays
     */
    public function addClosedPeriod(Carbon $from, Carbon $to): self
    {
        for($date = $from->copy(); $date <= $to; ) {
            if(!$this->isClosed($date)) {
                $this->closedDays[] = $date->format("Ymd");
            }
            $date = $date->addDay();
        }

        return $this;
    }

    /**
     * @param Carbon $date
     * @return BusinessDays
     */
    public function removeClosedDay(Carbon $date): self
    {
        if($k = array_search($date->format("Ymd"), $this->closedDays) !== false) {
            array_splice($this->closedDays, $k, 1);
        }

        return $this;
    }

    /**
     * @param Carbon $date
     * @return bool
     */
    public function isClosed(Carbon $date): bool
    {
        return array_search($date->format("Ymd"), $this->closedDays) !== false;
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     * @return int
     */
    public function daysBetween(Carbon $from, Carbon $to): int
    {
        return $from->diffInDaysFiltered(function(Carbon $day) {
            return $this->isOpenedDay($day);
        }, $to);
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     * @return int
     */
    public function hoursBetween(Carbon $from, Carbon $to): int
    {
        return $from->diffInHoursFiltered(function(Carbon $day) {
            return $this->isOpenedDay($day);
        }, $to);
    }

    /**
     * @param Carbon $date
     * @param int $days
     * @return Carbon
     */
    public function subDaysFrom(Carbon $date, int $days): Carbon
    {
        $resultDate = $date->copy();

        while($days > 0) {
            if($this->isOpenedDay($resultDate->subDay())) {
                $days--;
            }
        }

        return $resultDate;
    }

    /**
     * @param Carbon $date
     * @param int $days
     * @return Carbon
     */
    public function addDaysTo(Carbon $date, int $days): Carbon
    {
        $resultDate = $date->copy();

        while($days > 0) {
            if($this->isOpenedDay($resultDate->addDay())) {
                $days--;
            }
        }

        return $resultDate;
    }

    /**
     * @param Carbon $date
     * @return bool
     */
    public function isOpenedDay(Carbon $date): bool
    {
        return !$this->isWeekendDay($date)
            && !$this->isHoliday($date)
            && !$this->isClosed($date);
    }

    /**
     * @return array
     */
    public function getClosedDays()
    {
        return array_map(function($date) {
            return Carbon::createFromFormat('Ymd', $date)->startOfDay();
        }, $this->closedDays);
    }

    /**
     * @return array
     */
    public function getHolidays()
    {
        return array_map(function($date) {
            return Carbon::createFromFormat('Ymd', $date)->startOfDay();
        }, $this->holidays);
    }
}
