# A simple business days calculator

This package aims to count business working days between two [Carbon](https://github.com/briannesbitt/Carbon) dates.

## Usage

```php
$date = new BusinessDays();
    
// Set holidays (2018-1-1 is a Monday)
$date->addHoliday(Carbon::createFromDate(2018, 1, 1));
    
// Should return 9
$days = $date->daysBetween(
    Carbon::createFromDate(2018, 1, 1),
    Carbon::createFromDate(2018, 1, 15)
);
    
// Set a closed period (whole 2nd week)
$date->addClosedPeriod(
    Carbon::createFromDate(2018, 1, 8),
    Carbon::createFromDate(2018, 1, 12)
);

// Should return 5
$days = $date->daysBetween(
    Carbon::createFromDate(2018, 1, 1),
    Carbon::createFromDate(2018, 1, 15)
);
```
Default for weekend days are saturdays and sundays, but it can be configured:

```php
$date->setWeekendDays([Carbon::SUNDAY, Carbon::MONDAY]);
```
    
You can also add or subtract days from a given date:

```php
$newDate = $date->addDaysTo(
    Carbon::createFromDate(2018, 5, 14), 
    10
);
$newDate = $date->subDaysFrom(
    Carbon::createFromDate(2018, 5, 14), 
    10
);
```

## Installation

```
composer require code16/carbon-business-days
```

## License

MIT
